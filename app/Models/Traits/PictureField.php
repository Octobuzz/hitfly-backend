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
        if (null !== $this->user) { //todo check
            $userId = $this->user->id;

            return $this->getSaveFolder().DIRECTORY_SEPARATOR.$userId.DIRECTORY_SEPARATOR;
        }

        return $this->getSaveFolder().DIRECTORY_SEPARATOR;
    }

    public function getSaveFolder()
    {
        try {
            $reflection = new \ReflectionClass(get_class($this));
            $nameFolder = strtolower($reflection->getShortName()).'s';
        } catch (\ReflectionException $exception) {
            $nameFolder = 'defaultFolder';
            Log::warning($exception->getMessage());
        }

        return $nameFolder;
    }
}
