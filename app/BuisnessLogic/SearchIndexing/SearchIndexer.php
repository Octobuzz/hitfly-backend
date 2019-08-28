<?php

namespace App\BuisnessLogic\SearchIndexing;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SearchIndexer
{
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
                    '_index' => $indexName,
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
            }catch (Missing404Exception $e){
                Log::notice($e->getMessage(), $e->getTrace());
            }
        }


    }

    public function findIndexNameByAlias($aliasName)
    {
        $aliases = \Elasticsearch::indices()->getAliases();
        foreach ($aliases as $index => $aliasMapping) {
            if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
                return $index;
            }
        }

        return null;
    }

    public function getAllIndexesNames()
    {
        $aliases = \Elasticsearch::indices()->getAliases();
        foreach ($aliases as $index => $aliasMapping) {
            if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
                return $index;
            }
        }

        return null;
    }

    public function createAlias($nameIndex, $alias, $postfix, $removeOldAlias = false)
    {
        $params['body']['actions'] = [];
        if(false !== $removeOldAlias){//отвязка алиаса от старого индекса
            $params['body']['actions'][] = [
                'remove' => ['index' => $removeOldAlias ,'alias' => $alias.'_'.$postfix],
            ];
        }
        $params['body']['actions'][] = [
            'add' => ['index' => $nameIndex,'alias' => $alias.'_'.$postfix],
        ];
        try {
            $response = \Elasticsearch::indices()->updateAliases($params);
        }catch (\Exception $e){
            Log::notice($e->getMessage(), $e->getTrace());
            return false;
        }
        if($response['acknowledged'] === true) {
            return true;
        }
        return false;

    }

    public function createIndex($nameIndex, $postfix = false)
    {
        if($postfix === false){
            $postfix = time();
        }
        $params = [
            'index' => $nameIndex.'_'.$postfix,
        ];
        try {
            $response = \Elasticsearch::indices()->create($params);
        }catch (\Exception $e){
            Log::notice($e->getMessage(), $e->getTrace());
            return false;
        }
        if($response['acknowledged'] === true) {
            return $response['index'];
        }
        return false;

    }
}
