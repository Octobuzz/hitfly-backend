<?php

namespace App\BuisnessLogic\SearchIndexing;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SearchIndexer
{
    const POSTFIX_READ = '_read';
    const POSTFIX_WRITE = '_write';

    /**
     * Индексация сущности.
     *
     * @param $models
     *
     * @return mixed
     */
    public function index(Collection $models, string $indexName)
    {
        if (false === empty($model)) {
            return false;
        }
        $params = ['body' => []];
        $attribiteFiltering = new AttributeFiltering();
        foreach ($models as $model) {
            $filterName = 'filter'.(new \ReflectionClass($model))->getShortName();

            $params['body'][] = [
                'index' => [
                    '_index' => $indexName.self::POSTFIX_WRITE,
                    '_type' => get_class($model),
                    '_id' => $model->id,
                ],
            ];
            $params['body'][] = $attribiteFiltering->$filterName($model);
        }

        \Elasticsearch::bulk($params);
    }

    public function deleteFromIndex(Collection $models, string $indexName)
    {
        foreach ($models as $model) {
            $filterName = 'filter'.(new \ReflectionClass($model))->getShortName();

            $params = [
                    'index' => $indexName,
                    'type' => get_class($model),
                    'id' => $model->id,
            ];
            try {
                \Elasticsearch::delete($params);
            } catch (Missing404Exception $e) {
                Log::notice($e->getMessage(), $e->getTrace());
            }
        }
    }

    public function findIndexNameByAlias($aliasName): ?string
    {
        $aliases = \Elasticsearch::indices()->getAliases();
        foreach ($aliases as $index => $aliasMapping) {
            if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
                return $index;
            }
        }

        return null;
    }

//    public function getAllIndexesNames()
//    {
//        $aliases = \Elasticsearch::indices()->getAliases();
//        foreach ($aliases as $index => $aliasMapping) {
//            if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
//                return $index;
//            }
//        }
//
//        return null;
//    }

    public function createAlias($nameIndex, $alias, $postfix, $removeOldAlias = null): bool
    {
        $params['body']['actions'] = [];
        if (null !== $removeOldAlias) {//отвязка алиаса от старого индекса
            $existAlias = \Elasticsearch::indices()->existsAlias(['name' => $alias.$postfix, 'index' => $removeOldAlias]);
            if ($existAlias) {
                $params['body']['actions'][] = [
                    'remove' => ['index' => $removeOldAlias, 'alias' => $alias.$postfix],
                ];
            }
        }
        $params['body']['actions'][] = [
            'add' => ['index' => $nameIndex, 'alias' => $alias.$postfix],
        ];
        try {
            $response = \Elasticsearch::indices()->updateAliases($params);
        } catch (\Exception $e) {
            Log::notice($e->getMessage(), $e->getTrace());

            return false;
        }
        if (true === $response['acknowledged']) {
            return true;
        }

        return false;
    }

    public function createIndex($nameIndex, $postfix = false): ?string
    {
        if (false === $postfix) {
            $postfix = '_'.time();
        }
        $params = [
            'index' => $nameIndex.$postfix,
        ];
        try {
            $response = \Elasticsearch::indices()->create($params);
        } catch (\Exception $e) {
            Log::notice($e->getMessage(), $e->getTrace());

            return null;
        }
        if (true === $response['acknowledged']) {
            return $response['index'];
        }

        return null;
    }

    public function deleteIndex($nameIndex): ?string
    {
        $params = [
            'index' => $nameIndex,
        ];
        try {
            $response = \Elasticsearch::indices()->delete($params);
            dd($response);
        } catch (\Exception $e) {
            Log::notice($e->getMessage(), $e->getTrace());

            return null;
        }
        if (true === $response['acknowledged']) {
            return $response['index'];
        }

        return null;
    }

    public function filterIndexesByName($model): Collection
    {
        $indexArr = \Elasticsearch::cat()->indices(['index' => $model.'*']);
        if (empty($indexArr)) {
            return new Collection();
        }

        return collect($indexArr)->pluck('index')->sort();
    }
}
