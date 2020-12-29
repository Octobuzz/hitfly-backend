<?php

namespace App\Helpers;

use App\Models\Track;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileHelpers
{
    /**
     * @param $entity
     *
     * @return bool
     */
    public static function clearingStorage($entity)
    {
        try {
            if ($entity instanceof Track) {
                self::removeTrack($entity);
            }

            self::removeByMask($entity->getImage());

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * @param Track $track
     */
    public static function removeTrack(Track $track)
    {
        if (false === empty($track->filename)) {
            Storage::disk('public')->delete($track->filename);
        }
    }

    /**
     * удаляет файлы по маске(удобно удалять картинку со всеми ресайзами).
     *
     * @param string $file
     */
    private static function removeByMask(string $file): void
    {
        $path = pathinfo(Storage::disk('public')->path($file));
        array_map('unlink', glob($path['dirname'].DIRECTORY_SEPARATOR.$path['filename'].'_*'));
    }
}
