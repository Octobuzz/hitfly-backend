<?php

namespace App\Repositories;

use App\Interfaces\Repository\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    public function getModelClass()
    {
        return $this->model;
    }
}
