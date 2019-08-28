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
            $this->indexer = new SearchIndexer();
//            $alias = $this->indexer->createAlias($argumentModel,'new2');
//            dd($alias);
            // получим имя индекса по алиасу
            $oldIndexName = self::getIndexNameByAlias($argumentModel);
            if(null === $oldIndexName){//создадим алиас, если его нет
                if(false === $this->indexer->createAlias($oldIndexName, $argumentModel, 'read')){
                    $this->error('Невозможно создать алиас '.$argumentModel.'_read');
                    die();
                }
                $indexName = $alias;
            }
            //создадим новый индекс
            $newIndexName = $this->indexer->createIndex($oldIndexName);
            if(false === $newIndexName){
                $this->error('Ошибка создания нового индекса');
                die();
            }
            //создадим алиас для нового индекса с отвязкой алиаса для записи со старого индекса
            if(false === $this->indexer->createAlias($newIndexName, 'write', $oldIndexName)){
                $this->error('Ошибка создания алиаса для записи в новый индекс');
                die();
            }
            dd('стапэ');
            $config[$argumentModel]['model']::chunk(self::CHUNK_SIZE, function ($collectionModel) use ($argumentModel) {
                $this->indexer->index($collectionModel, $argumentModel);
            }
                );
            $this->line("<info>Переиндексирована модель:</info> {$argumentModel}");
        } elseif ('all' === $argumentModel) {
            foreach ($config as $indexName => $modelClass) {
                $this->indexer = new SearchIndexer();
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

    private function getIndexNameByAlias($alias, $postfix = 'read')
    {
        $indexName = $this->indexer->findIndexNameByAlias($alias.'_'.$postfix);
        if(null === $indexName){//создадим алиас, если его нет
            if(false === $this->indexer->createAlias($alias, $postfix)){
                $this->error('Невозможно создать алиас '.$alias.'_'.$postfix);
                die();
            }
            $indexName = $alias;
        }
        return $indexName;
    }

    private function getlastIndexName($model)
    {
        $indexName = $this->indexer->findIndexNameByAlias($alias.'_'.$postfix);
        if(null === $indexName){//создадим алиас, если его нет
            if(false === $this->indexer->createAlias($alias, $postfix)){
                $this->error('Невозможно создать алиас '.$alias.'_'.$postfix);
                die();
            }
            $indexName = $alias;
        }
        return $indexName;
    }






}
