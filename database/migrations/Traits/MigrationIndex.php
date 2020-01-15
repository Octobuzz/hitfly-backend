<?php

namespace Migrations\Traits;

trait MigrationIndex
{
    /**
     * Безопасное удаление индекса NestedSet.
     *
     * @param $tableName
     * @param $indexName
     */
    public function _dropIndexIfExist($tableName, $indexName)
    {
        Schema::table($tableName, function (Blueprint $table) use ($tableName, $indexName) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails($tableName);

            $columns = \Kalnoy\Nestedset\NestedSet::getDefaultColumns();
            if ($doctrineTable->hasIndex($indexName)) {
                $table->dropIndex($indexName);
            }
            $table->dropColumn($columns);
        });
    }
}
