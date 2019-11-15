<?php

namespace App\Repositories;

class SearchRepository extends BaseRepository
{
    public function getDataFromModelWithRelevance(array $ids, int $limit = 0, int $page = 0)
    {
        $query = $this->getModelClass()::query()->whereIn('id', $ids)->orderByRaw('FIELD(`id`, '.implode(',', $ids).')');
        if ($limit > 0 && $page > 0) {
            return $query->paginate($limit, ['*'], 'page', $page);
        } else {
            return $query->get();
        }
    }
}
