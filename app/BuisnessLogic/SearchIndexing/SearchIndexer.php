<?php

namespace App\BuisnessLogic\SearchIndexing;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use ReflectionException;

class SearchIndexer
{
    public const POSTFIX_READ = '_read';
    public const POSTFIX_WRITE = '_write';

    /**
     * Индексация сущности.
     *
     * @param Collection $models
     * @param string     $indexName
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public function index(Collection $models, string $indexName)
    {
        try {
            if (true === $models->isEmpty()) {
                return false;
            }
            $this->checkAndCreateAlias($indexName, self::POSTFIX_WRITE);
            $this->checkAndCreateAlias($indexName, self::POSTFIX_READ);
            $params = ['body' => []];
            $attribiteFiltering = new AttributeFiltering();
            $filterName = 'filter'.(new \ReflectionClass($models->first()))->getShortName();
            $typeIndex = get_class($models->first());
            $indexNameWithPostfix = $indexName.self::POSTFIX_WRITE;
            foreach ($models as $model) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $indexNameWithPostfix,
                        '_type' => $typeIndex,
                        '_id' => $model->id,
                    ],
                ];
                $params['body'][] = $attribiteFiltering->$filterName($model);
            }

            \Elasticsearch::bulk($params);
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }

    /**
     * удаление коллекции.
     *
     * @param Collection $models
     * @param string     $indexName
     *
     * @return bool
     */
    public function deleteFromIndex(Collection $models, string $indexName)
    {
        try {
            if (true === $models->isEmpty()) {
                return false;
            }
            $indexNameWrite = $indexName.self::POSTFIX_WRITE;
            $indexNameRead = $indexName.self::POSTFIX_READ;
            foreach ($models as $model) {
                $paramsRead = $this->getIndexParams($indexNameRead, $model);
                $e = $this->deleteElement($paramsRead);
                //удалим из из индекса WRITE
                $paramsWrite = $this->getIndexParams($indexNameWrite, $model);
                $e = $this->deleteElement($paramsWrite);
            }
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
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
            if (true === $response['acknowledged']) {
                return true;
            }
        } catch (\Exception $e) {
            Log::notice($e->getMessage(), $e->getTrace());
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
                if (true === $response['acknowledged']) {
                    return $response['index'];
                }
            } catch (\Exception $e) {
                Log::notice($e->getMessage(), $e->getTrace());
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
        if (false === \Elasticsearch::indices()->exists($params)) {
            return false;
        }

        try {
            $response = \Elasticsearch::indices()->delete($params);
            if (true === $response['acknowledged']) {
                return true;
            }
        } catch (\Exception $e) {
            Log::notice($e->getMessage(), $e->getTrace());
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
            return null;
        }

        if (false === $this->createAlias($newIndexName, $modelName, $postfix)) {
            return null;
        }

        return $modelName.$postfix;
    }

    /**
     * @param string $indexName
     * @param $model
     *
     * @return array
     */
    private function getIndexParams(string $indexName, $model): array
    {
        $params = [
            'index' => $indexName,
            'type' => get_class($model),
            'id' => $model->id,
        ];

        return $params;
    }

    /**
     * @param array $params
     *
     * @return Missing404Exception|\Exception
     */
    private function deleteElement(array $params)
    {
        if (true === \Elasticsearch::exists($params)) {
            try {
                \Elasticsearch::delete($params);
            } catch (Missing404Exception $e) {
                Log::notice($e->getMessage(), $e->getTrace());
                return $e->getMessage();
            }
        }

        return;
    }
}
