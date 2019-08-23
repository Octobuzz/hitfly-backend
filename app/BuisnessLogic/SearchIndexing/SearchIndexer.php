<?php

namespace App\BuisnessLogic\SearchIndexing;

class SearchIndexer
{
    /**
     * Индексация сущности.
     *
     * @param $models
     *
     * @return mixed
     */
    public function index(\Illuminate\Database\Eloquent\Collection $models, string $indexName)
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
}
