<?php

namespace App\BuisnessLogic\Services\SearchService;

use App\Contracts\Search\SearchContract;

class ElasticsearchService implements SearchContract
{
    public function search(string $q)
    {
        $result = [];

        $models = [
           ['index' => 'album_read', 'fields' => 'title'],
           ['index' => 'track_read', 'fields' => 'track_name'],
           ['index' => 'user_read', 'fields' => 'username'],
        ];
        foreach ($models as $model) {
            $params['body'][] = [
                'index' => $model['index'],
            ];
            $params['body'][] = ['query' => [
                                        'multi_match' => [
                                            'fuzziness' => 'AUTO',
                                            'query' => $q,
                                            'fields' => $model['fields'],
                                        ],
                                    ],
                                ];
        }
        //echo"<pre>";var_dump($params);die();
        $result = \Elasticsearch::msearch($params);
        //return $result;
        $idsArr = $this->extractionResult($result);
        $data = $this->getDataFromDB($idsArr);


        return $data;
    }

    private function extractionResult(array $result):array
    {
        $ids = [];
        foreach ($result['responses'] as $index) {
            foreach ($index['hits']['hits'] as $hit){
                $ids[$hit['_type']][] = $hit['_id'];
            }
        }
        return $ids;
    }

    private function getDataFromDB(array $idsArr)
    {
        $data = [];
        foreach ($idsArr as $model => $ids){
            $data[$model] = $model::query()->whereIn('id',$ids)->orderByRaw("FIELD(`id`, ".implode(",", $ids).")")->get();
        }
        return $data;
    }
}
