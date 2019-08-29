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
        $argumentModel = Str::snake(trim($this->argument('model')));
        $config = config('search.models');
        if (array_key_exists($argumentModel, $config)) {
            //блок создания первоначальных индексов(если нет ни одног индекса, алиаса и т.п.)
            // получим имя индекса по алиасу
            //$oldIndexName = self::getIndexNameByAlias($argumentModel);
            $oldIndexName = $this->indexer->findIndexNameByAlias($argumentModel.SearchIndexer::POSTFIX_READ);
            if (null === $oldIndexName) {//у индекса может не быть алиаса
                $oldIndexName = $this->getLastIndexName($argumentModel); //получим имя самого последнего индекса
                if (null === $oldIndexName) {//если индекса не существует
                    $this->info('Нет старого индекса. '.$argumentModel.'_***');
                } elseif (false === $this->indexer->createAlias($oldIndexName, $argumentModel, SearchIndexer::POSTFIX_READ)) {
                    $this->error('Невозможно создать алиас '.$argumentModel.'_read');
                    die();
                }
            }
            //создадим новый индекс(сюда будет вестись переиндексация)
            $newIndexName = $this->indexer->createIndex($argumentModel);
            if (null === $newIndexName) {
                $this->error('Ошибка создания нового индекса');
                die();
            }
            //создадим алиас для нового индекса с отвязкой алиаса для записи со старого индекса
            if (false === $this->indexer->createAlias($newIndexName, $argumentModel, SearchIndexer::POSTFIX_WRITE, $oldIndexName)) {
                $this->error('Ошибка создания алиаса для записи в новый индекс');
                die();
            }
            $config[$argumentModel]['model']::chunk(self::CHUNK_SIZE, function ($collectionModel) use ($argumentModel) {
                $this->indexer->index($collectionModel, $argumentModel);
            }
            );
            //установка алиаса на чтение на новый индекс
            if (false === $this->indexer->createAlias($newIndexName, $argumentModel, SearchIndexer::POSTFIX_READ, $oldIndexName)) {
                $this->error('Ошибка создания алиаса чтения в новый индекс(переиндексированый)');
                die();
            }

            //удаление старого индекса
            if (null !== $oldIndexName && null === $this->indexer->deleteIndex($oldIndexName)) {
                $this->error('Ошибка создания алиаса чтения в новый индекс(переиндексированый)');
                die();
            }
            $this->line("<info>Переиндексирована модель:</info> {$argumentModel}");
        } elseif ('all' === $argumentModel) {
            foreach ($config as $indexName => $modelClass) {
                $config[$indexName]['model']::chunk(self::CHUNK_SIZE, function ($collectionModel) use ($indexName) {
                    $this->indexer->index($collectionModel, $indexName);
                }
                );
            }
            $this->line('Переиндексирован весь индекс');
        } else {
            $this->error('Ошибка в имени индексируемой сущности');
        }
    }

//    private function getIndexNameByAlias($alias, $postfix = SearchIndexer::POSTFIX_READ): ?string
//    {
//        $indexName = $this->indexer->findIndexNameByAlias($alias.'_'.$postfix);
//
//        return $indexName;
//    }

    private function getLastIndexName($model): ?string
    {
        $namesOfindexes = $this->indexer->filterIndexesByName($model);
        if ($namesOfindexes->isEmpty()) {
            return null;
        }

        return $namesOfindexes->pop();
    }
}
