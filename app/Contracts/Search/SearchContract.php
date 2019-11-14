<?php

namespace App\Contracts\Search;

use Rebing\GraphQL\Support\SelectFields;

interface SearchContract
{
    /**
     * поиск по всем сущностям одновременно.
     *
     * @param string $query
     * @param int    $limit
     *
     * @return mixed
     */
    public function search(string $query, int $limit);

    /**
     * поиск по одной сущности.
     *
     * @param array        $args
     * @param SelectFields $fields
     *
     * @return mixed
     */
    public function searchEssence(array $args, SelectFields $fields);
}
