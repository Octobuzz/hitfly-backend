<?php

namespace App\BuisnessLogic\Services\SearchService;

use App\Contracts\Search\SearchContract;
use Rebing\GraphQL\Support\SelectFields;

class ElasticsearchService implements SearchContract
{
    public $args;
    public $fields;
    const POSTFIX_READ = '_read';
    //настройки моделей и полей для поиска
    const MODELS = [
            'album' => ['index' => 'album_read', 'fields' => 'title'],
            'track' => ['index' => 'track_read', 'fields' => 'track_name'],
            'user' => ['index' => 'user_read', 'fields' => 'username'],
        ];

    public function search(string $q, int $limit = 3)
    {
        $data = [];

        foreach (self::MODELS as $model) {
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
                                'size' => $limit,
                                ];
        }
        $result = [];
        $result = \Elasticsearch::msearch($params);
        $idsArr = $this->extractionMultiSearchResult($result);
        if (!empty($idsArr)) {
            $data = $this->getDataFromDB($idsArr);
        }

        return $data;
    }

    public function searchEssence(array $args, SelectFields $fields)
    {
        $this->args = $args;
        $this->fields = $fields;
        $data = [];

        $params = [
            'index' => $this->args['essence'].self::POSTFIX_READ,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fuzziness' => 'AUTO',
                        'query' => $this->args['q'],
                        'fields' => self::MODELS[$this->args['essence']]['fields'],
                                ],
                        ],
                    ],
        ];

        $result = [];
        $result = \Elasticsearch::search($params);
        $idsArr = $this->extractionSingleSearchResult($result);

        if (!empty($idsArr)) {
            $data = $this->getDataSingleSearchFromDB($idsArr);
        }

        return $data;
    }

    /**
     * извлечение результатов из мульти поиска.
     *
     * @param array $result
     *
     * @return array
     */
    private function extractionMultiSearchResult(array $result): array
    {
        $ids = [];
        foreach ($result['responses'] as $index) {
            if (!empty($index['hits'])) {
                foreach ($index['hits']['hits'] as $hit) {
                    $ids[$hit['_type']][] = $hit['_id'];
                }
            }
        }

        return $ids;
    }

    /**
     * извлечение результатов из обычного поиска.
     *
     * @param array $result
     *
     * @return array
     */
    private function extractionSingleSearchResult(array $result): array
    {
        $ids = [];
        if (!empty($result['hits'])) {
            foreach ($result['hits']['hits'] as $hit) {
                $ids[$hit['_type']][] = $hit['_id'];
            }
        }

        return $ids;
    }

    private function getDataFromDB(array $idsArr)
    {
        $data = [];
        foreach ($idsArr as $model => $ids) {
            $data[$model] = $model::query()->whereIn('id', $ids)->orderByRaw('FIELD(`id`, '.implode(',', $ids).')')->get();
        }

        return $data;
    }

    private function getDataSingleSearchFromDB(array $idsArr)
    {
        $ids = reset($idsArr);
        $model = key($idsArr);
        $data = $model::with($this->fields->getRelations())->whereIn('id', $ids)->orderByRaw('FIELD(`id`, '.implode(',', $ids).')')
            ->paginate($this->args['limit'], ['*'], 'page', $this->args['page']);

        return $data;
    }
}
