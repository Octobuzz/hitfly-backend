<?php

namespace App\Factories;

use App\Models\Album;
use App\Models\Track;
use App\Repositories\AlbumRepository;
use App\Repositories\TrackRepository;
use App\Repositories\UserRepository;
use App\User;

class RepositoryFactory
{
    public static function getRepository(string $modelName)
    {
        switch ($modelName) {
            case Album::class:
                $repository = app()->make(AlbumRepository::class);
                break;
            case Track::class:
                $repository = app()->make(TrackRepository::class);
                break;
            case User::class:
                $repository = app()->make(UserRepository::class);
                break;
            default:
                throw new \InvalidArgumentException('Нет репозитория нужного типа');
                break;
        }

        return $repository;
    }
}
