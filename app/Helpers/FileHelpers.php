<?php

namespace App\Helpers;

use App\Models\Track;
use Illuminate\Support\Facades\Storage;

class FileHelpers
{
    public static function clearingStorage($entity)
    {
        switch ($entity) {
            case Track::class:
                self::clearingTrackStorage($entity);
                break;
            default:
                if (null !== $entity->getImage()) {
                    self::removeByMask($entity->getImage());
                }
        }
    }

    public static function clearingTrackStorage(Track $track)
    {
        if (!empty($track->filename)) {
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
