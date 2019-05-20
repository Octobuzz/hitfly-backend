<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Log;

trait PictureField
{
    abstract public function getImage(): ?string;

    abstract public function user();

    public function getSizePicture($size): array
    {
        return [config('image.size.allSizes.'.$size.'.width'), config('image.size.allSizes.'.$size.'.height')];
    }

    public function getPath(): string
    {
        $userId = $this->user->id;

        return $this->getSaveFolder().DIRECTORY_SEPARATOR.$userId.DIRECTORY_SEPARATOR;
    }

    public function getSaveFolder()
    {
        $nameFolder = 'defaultFolder';
        try {
            $reflection = new \ReflectionClass(get_class($this));
            $nameFolder = strtolower($reflection->getShortName()).'s';
        } catch (\ReflectionException $exception) {
            Log::warning($exception->getMessage());
        }

        return $nameFolder;
    }
}
