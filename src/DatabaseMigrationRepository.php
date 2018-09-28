<?php

namespace Hafael\Hotel;

use Illuminate\Database\ConnectionResolverInterface as Resolver;

class DatabaseMigrationRepository implements MigrationRepositoryInterface
{
    /**
     * The database connection resolver instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver;

    /**
     * The name of the migration table.
     *
     * @var string
     */
    protected $table;

    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new database migration repository instance.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $resolver
     * @param  string  $table
     * @return void
     */
    public function __construct(Resolver $resolver, $table)
    {
        $this->table = $table;
        $this->resolver = $resolver;
    }

    /**
     * Get the completed migrations.
     *
     * @return array
     */
    public function getRan($tenantId = null)
    {
        $tenantIdColumn = config('hotel.migration_table_tenant_id_column');

        return $this->table()
                ->when($tenantId, function($query, $tenantId) {
                    $query->where($tenantIdColumn, $tenantId);
                })
                ->orderBy('batch', 'asc')
                ->orderBy('migration', 'asc')
                ->pluck('migration')->all();
    }

    /**
     * Get list of migrations.
     *
     * @param  int  $steps
     * @return array
     */
    public function getMigrations($steps)
    {
        $query = $this->table()->where('batch', '>=', '1');

        return $query->orderBy('batch', 'desc')
                     ->orderBy('migration', 'desc')
                     ->take($steps)->get()->all();
    }

    /**
     * Get the last migration batch.
     * 
     * @param  string  $tenantId
     *
     * @return array
     */
    public function getLast($tenantId = null)
    {
        $tenantIdColumn = config('hotel.migration_table_tenant_id_column');
        
        $query = $this->table()
                      ->when($tenantId, function($query, $tenantId) {
                            $query->where($tenantIdColumn, $tenantId);
                      })
                      ->where('batch', $this->getLastBatchNumber($tenantId));

        return $query->orderBy('migration', 'desc')->get()->all();
    }

    /**
     * Get the completed migrations with their batch numbers.
     *
     * @return array
     */
    public function getMigrationBatches()
    {
        return $this->table()
                ->orderBy('batch', 'asc')
                ->orderBy('migration', 'asc')
                ->pluck('batch', 'migration')->all();
    }

    /**
     * Log that a migration was run.
     *
     * @param  string  $file
     * @param  int  $batch
     * @param  string  $tenantId
     * @return void
     */
    public function log($file, $batch, $tenantId)
    {
        $tenantIdColumn = config('hotel.migration_table_tenant_id_column');
        
        $record = ['migration' => $file, 'batch' => $batch, $tenantIdColumn => $tenantId];

        $this->table()->insert($record);
    }

    /**
     * Remove a migration from the log.
     *
     * @param  object  $migration
     * @return void
     */
    public function delete($migration)
    {
        $this->table()->where('migration', $migration->migration)->delete();
    }

    /**
     * Get the next migration batch number.
     * 
     * @param  string  $tenantId
     *
     * @return int
     */
    public function getNextBatchNumber($tenantId = null)
    {
        return $this->getLastBatchNumber($tenantId) + 1;
    }

    /**
     * Get the last migration batch number.
     *
     * @param  string  $tenantId
     * 
     * @return int
     */
    public function getLastBatchNumber($tenantId = null)
    {
        $tenantIdColumn = config('hotel.migration_table_tenant_id_column');

        return $this->table()
                    ->when($tenantId, function($query, $tenantId) {
                        $query->where($tenantIdColumn, $tenantId);
                    })
                    ->max('batch');
    }

    /**
     * Create the migration repository data store.
     *
     * @return void
     */
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $tenantIdColumn = config('hotel.migration_table_tenant_id_column');

        $tenantIdMigrationType = config('hotel.tenant_id_migration_type');

        $schema->create($this->table, function ($table) {
            // The migrations table is responsible for keeping track of which of the
            // migrations have actually run for the application. We'll create the
            // table to hold the migration file's path as well as the batch ID.
            $table->increments('id');
            $table->{$tenantIdMigrationType}($tenantIdColumn)->nullable();
            $table->string('migration');
            $table->integer('batch');
        });
    }

    /**
     * Determine if the migration repository exists.
     *
     * @return bool
     */
    public function repositoryExists()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        return $schema->hasTable($this->table);
    }

    /**
     * Get a query builder for the migration table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->getConnection()->table($this->table)->useWritePdo();
    }

    /**
     * Get the connection resolver instance.
     *
     * @return \Illuminate\Database\ConnectionResolverInterface
     */
    public function getConnectionResolver()
    {
        return $this->resolver;
    }

    /**
     * Resolve the database connection instance.
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        return $this->resolver->connection($this->connection);
    }

    /**
     * Set the information source to gather data.
     *
     * @param  string  $name
     * @return void
     */
    public function setSource($name)
    {
        $this->connection = $name;
    }
}
