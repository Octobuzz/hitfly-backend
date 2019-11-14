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
                $repository = new AlbumRepository();
                break;
            case Track::class:
                $repository = new TrackRepository();
                break;
            case User::class:
                $repository = new UserRepository();
                break;
            default:
                throw new \Exception('Нет репозитория нужного типа');
                break;
        }

        return $repository;
    }
}
