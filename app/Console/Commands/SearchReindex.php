<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\BuisnessLogic\SearchIndexing\SearchIndexer;

class SearchReindex extends Command
{
    const CHUNK_SIZE = 100;
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
            $indexer = new SearchIndexer();
            $config[$argumentModel]['model']::chunk(self::CHUNK_SIZE, function ($collectionModel) use ($indexer, $argumentModel) {
                $indexer->index($collectionModel, $argumentModel);
            }
                );
            $this->line("<info>Переиндексирована модель:</info> {$argumentModel}");
        } elseif ('all' === $argumentModel) {
            foreach ($config as $indexName => $modelClass) {
                $indexer = new SearchIndexer();
                $config[$indexName]['model']::chunk(self::CHUNK_SIZE, function ($collectionModel) use ($indexer, $indexName) {
                    $indexer->index($collectionModel, $indexName);
                }
                );
            }
            $this->line('Переиндексирован весь индекс');
        } else {
            $this->error('Ошибка в имени индексируемой сущности');
        }
    }
}
