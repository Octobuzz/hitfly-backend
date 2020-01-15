<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Fluent;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    //use DatabaseMigrations;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->hotfixSqlite();
    }
    protected function setUp()
    {
        parent::setUp();

//        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
//            DB::statement(DB::raw('PRAGMA foreign_keys=1'));
//        }
//        $this->artisan('migrate:fresh');
//
//        $this->app[Kernel::class]->setArtisan(null);
//
//        $this->beforeApplicationDestroyed(function () {
//            $this->artisan('migrate:rollback');
//
//            RefreshDatabaseState::$migrated = false;
//        });

    }


    /**
     * Нашел такой костыль. ПРобелма была в том, что:
     *
     * BadMethodCallException: SQLite doesn't support dropping foreign keys (you would need to re-create the table).
     *
     *
     */
    public function hotfixSqlite()
    {


        Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }

                    return new class($this) extends SQLiteBuilder {
                        protected function createBlueprint($table, \Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }

}
