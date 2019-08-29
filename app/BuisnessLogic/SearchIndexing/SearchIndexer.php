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
     * @param Collection $models
     * @param string     $indexName
     *
     * @return mixed
     */
    public function index(Collection $models, string $indexName)
    {
        if (true === $models->isEmpty()) {
            return false;
        }
        $this->checkAndCreateAlias($indexName, self::POSTFIX_WRITE);
        $this->checkAndCreateAlias($indexName, self::POSTFIX_READ);
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

    /**
     * удаление коллекции.
     *
     * @param Collection $models
     * @param string     $indexName
     *
     * @throws \ReflectionException
     */
    public function deleteFromIndex(Collection $models, string $indexName)
    {
        foreach ($models as $model) {
            $filterName = 'filter'.(new \ReflectionClass($model))->getShortName();

            $params = [
                    'index' => $indexName.self::POSTFIX_READ,
                    'type' => get_class($model),
                    'id' => $model->id,
            ];
            if (true === \Elasticsearch::exists($params)) {
                try {
                    \Elasticsearch::delete($params);
                } catch (Missing404Exception $e) {
                    Log::notice($e->getMessage(), $e->getTrace());
                }
            }
            //удалим из из индекса WRITE
            $params = [
                'index' => $indexName.self::POSTFIX_WRITE,
                'type' => get_class($model),
                'id' => $model->id,
            ];
            if (true === \Elasticsearch::exists($params)) {
                try {
                    \Elasticsearch::delete($params);
                } catch (Missing404Exception $e) {
                    Log::notice($e->getMessage(), $e->getTrace());
                }
            }
        }
    }

    /**
     * поиск имени индекса по алиасу.
     *
     * @param string $aliasName
     *
     * @return string|null
     */
    public function findIndexNameByAlias(string $aliasName): ?string
    {
        $aliases = \Elasticsearch::indices()->getAliases();
        foreach ($aliases as $index => $aliasMapping) {
            if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
                return $index;
            }
        }

        return null;
    }

    /**
     * создание псевдонима.
     *
     * @param $nameIndex
     * @param $alias
     * @param $postfix
     * @param null $removeOldAlias
     *
     * @return bool
     */
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

    /**
     * создание индекса.
     *
     * @param $nameIndex
     * @param bool $postfix
     *
     * @return string|null
     */
    public function createIndex($nameIndex, $postfix = false): ?string
    {
        if (false === $postfix) {
            $postfix = '_'.time();
        }
        $params = [
            'index' => $nameIndex.$postfix,
        ];
        if (false === \Elasticsearch::indices()->exists($params)) {
            try {
                $response = \Elasticsearch::indices()->create($params);
            } catch (\Exception $e) {
                Log::notice($e->getMessage(), $e->getTrace());

                return null;
            }
            if (true === $response['acknowledged']) {
                return $response['index'];
            }
        }

        return null;
    }

    /**
     * удаление индекса.
     *
     * @param $nameIndex
     *
     * @return bool|null
     */
    public function deleteIndex($nameIndex): ?bool
    {
        $params = [
            'index' => $nameIndex,
        ];
        if (true === \Elasticsearch::indices()->exists($params)) {
            try {
                $response = \Elasticsearch::indices()->delete($params);
            } catch (\Exception $e) {
                Log::notice($e->getMessage(), $e->getTrace());

                return false;
            }
            if (true === $response['acknowledged']) {
                return true;
            }
        }

        return false;
    }

    public function filterIndexesByName($model): Collection
    {
        $indexArr = \Elasticsearch::cat()->indices(['index' => $model.'*']);
        if (empty($indexArr)) {
            return new Collection();
        }

        return collect($indexArr)->pluck('index')->sort();
    }

    public function checkAndCreateAlias($modelName, $postfix): ?string
    {
        $oldIndexName = $this->findIndexNameByAlias($modelName.$postfix);

        if (null !== $oldIndexName) {
            return $modelName.$postfix;
        }

        $namesOfindexes = $this->filterIndexesByName($modelName);
        if ($namesOfindexes->isNotEmpty()) {
            $newIndexName = $namesOfindexes->pop();
        } else {
            $newIndexName = $this->createIndex($modelName);
        }

        if (null !== $newIndexName) {
            if (false === $this->createAlias($newIndexName, $modelName, $postfix)) {
                return null;
            }

            return $modelName.$postfix;
        }

        return null;
    }
}
