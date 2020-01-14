<?php

namespace App\Helpers;

use App\Models\Track;
use Illuminate\Support\Facades\Storage;

class FileHelpers
{
    /**
     * @param $entity
     */
    public static function clearingStorage($entity)
    {
        if ($entity instanceof Track) {
            self::clearingTrackStorage($entity);
        } elseif (null !== $entity->getImage()) {
            self::removeByMask($entity->getImage());
        }
    }

    /**
     * @param Track $track
     */
    public static function clearingTrackStorage(Track $track)
    {
        if (false === empty($track->filename)) {
            Storage::disk('public')->delete($track->filename);
        }

        if (Storage::disk('public')->exists($track->cover)) {
            self::removeByMask($track->getImage());
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
