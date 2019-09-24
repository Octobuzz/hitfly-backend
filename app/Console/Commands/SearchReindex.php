<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\BuisnessLogic\SearchIndexing\SearchIndexer;

class SearchReindex extends Command
{
    const CHUNK_SIZE = 100;
    private $indexer;
    /**
     * Переиндексайия поиска.
     *
     * @var string
     */
    protected $signature = 'hitfly:search:reindex {model=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Переиндексация Elasticsearch';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->indexer = new SearchIndexer();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $modelName = Str::snake(trim($this->argument('model')));
            $config = config('search.models');

            if ('all' === $modelName) {//индекс всех моделей
                foreach ($config as $indexName => $modelClass) {
                    $this->reindexModel($indexName, $config[$indexName]);
                }
                $this->line('Переиндексирован весь индекс');

                return;
            }

            if (!array_key_exists($modelName, $config)) {//проверка существования имени модели в конфиге
                $this->error('Ошибка в имени индексируемой сущности');

                return;
            }

            //индекс конкретной модели
            $this->reindexModel($modelName, $config[$modelName]);
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $e->getTrace());
        }
    }

    private function getLastIndexName($model): ?string
    {
        $namesOfindexes = $this->indexer->filterIndexesByName($model);
        if ($namesOfindexes->isEmpty()) {
            return null;
        }

        return $namesOfindexes->pop();
    }

    /**
     * переиндексация модели
     * 1 создаем новый индекс
     * 2 отвязываем alias записи от старого индекса и присваиваем его к новому
     * 3 заполняем новый индекс данными
     * 4 устанввливаем alias на чтение на новый индекс
     * 5 удаляем старый индекс
     *
     * @param string $modelName
     * @param array  $config
     */
    private function reindexModel(string $modelName, array $config): void
    {
        //инициируем индекс, алиасы и т.п.
        $oldIndexName = $this->initiateIndex($modelName);
        //создадим новый индекс(сюда будет вестись переиндексация)
        $newIndexName = $this->indexer->createIndex($modelName);
        if (null === $newIndexName) {
            throw new \Exception('Ошибка создания нового индекса');
        }
        //создадим алиас для записи(write) для нового индекса с отвязкой алиаса для записи(write) со старого индекса
        if (false === $this->indexer->createAlias($newIndexName, $modelName, SearchIndexer::POSTFIX_WRITE, $oldIndexName)) {
            throw new \Exception('Ошибка создания алиаса для записи в новый индекс');
        }
        $query = $config['model']::query();
        //добавление scope если заданы в конфиге
        if (isset($config['scopes']) && !empty($config['scopes'])) {
            foreach ($config['scopes'] as $scope => $param) {
                if (!empty($param)) {
                    $query->$scope($param);
                } else {
                    $query->$scope();
                }
            }
        }
        $query->chunk(self::CHUNK_SIZE, function ($collectionModel) use ($modelName) {
            $this->indexer->index($collectionModel, $modelName);
        }
        );
        //установка алиаса на чтение на новый индекс
        if (false === $this->indexer->createAlias($newIndexName, $modelName, SearchIndexer::POSTFIX_READ, $oldIndexName)) {
            throw new \Exception('Ошибка создания алиаса чтения в новый индекс(переиндексированый)');
        }

        //удаление старого индекса
        if (null !== $oldIndexName && false === $this->indexer->deleteIndex($oldIndexName)) {
            throw new \Exception('Ошибка удаления старого индекса');
        }
        $this->line("<info>Переиндексирована модель:</info> {$modelName}");
    }

    /**
     * создание первоначальных индексов(если нет ни одног индекса, алиаса и т.п.).
     *
     * @param string $modelName
     *
     * @return string|null
     */
    private function initiateIndex(string $modelName)
    {
        // получим имя индекса по алиасу
        $oldIndexName = $this->indexer->findIndexNameByAlias($modelName.SearchIndexer::POSTFIX_READ);
        if (null !== $oldIndexName) {
            return $oldIndexName;
        }

        //у индекса может не быть алиаса
        $oldIndexName = $this->getLastIndexName($modelName); //получим имя самого последнего индекса
        if (null === $oldIndexName) {//если индекса не существует, вывод сообщения и создание алиаса
            $this->info('Нет старого индекса. '.$modelName.'_***');
        } elseif (false === $this->indexer->createAlias($oldIndexName, $modelName, SearchIndexer::POSTFIX_READ)) {
            throw new \Exception('Невозможно создать алиас '.$modelName.'_read');
        }

        return $oldIndexName;
    }
}
