<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DeleteResizesImage extends Command
{
    private $folders = [
        'albums',
        'avatars',
        'collection',
        'collections',
        'musicgroups',
        'products',
        'tracks',
    ];

    /**
     * удаление ресайзнутых картинок.
     *
     * @var string
     */
    protected $signature = 'hitfly:image:delete {folder=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление resize картинок';

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
            $folderName = Str::snake(trim($this->argument('folder')));

            if ('all' === $folderName) {//очистить все папки
                foreach ($this->folders as $currFolder) {
                    $this->clearFolder($currFolder);
                }
                $this->line('Удалены ресайзы картинок во всех директориях');

                return;
            }

            if (!isset($folderName, $this->folders)) {
                $this->error('Ошибка в имени директории');

                return;
            }

            $this->clearFolder($folderName);
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $e->getTrace());
        }
    }

    private function clearFolder($folderName)
    {
        $path = Storage::disk('public')->getAdapter()->getPathPrefix();
        $imagePath = $path."{$folderName}/*/*_size_*";
        array_map('unlink', glob($imagePath));
    }
}
