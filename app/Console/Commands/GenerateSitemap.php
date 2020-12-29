<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BuisnessLogic\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * Переиндексайия поиска.
     *
     * @var string
     */
    protected $signature = 'hitfly:sitemap:xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация sitemap XML';

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
        try {
            $sitemapGenerator = new SitemapGenerator();

            $sitemapGenerator->generateMap();
            $this->line('Создана карта сайта XML');
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $e->getTrace());
        }
    }
}
