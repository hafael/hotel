<?php

namespace Hafael\Hotel;

interface MigrationRepositoryInterface
{
    /**
     * Get the completed migrations.
     * 
     * @param  string  $tenantId
     * @return array
     */
    public function getRan($tenantId = null);

    /**
     * Get list of migrations.
     *
     * @param  int  $steps
     * @return array
     */
    public function getMigrations($steps);

    /**
     * Get the last migration batch.
     *
     * @param  string  $tenantId
     * @return array
     */
    public function getLast($tenantId = null);

    /**
     * Get the completed migrations with their batch numbers.
     *
     * @return array
     */
    public function getMigrationBatches();

    /**
     * Log that a migration was run.
     *
     * @param  string  $file
     * @param  int  $batch
     * @param  string  $tenantId
     * @return void
     */
    public function log($file, $batch, $tenantId);

    /**
     * Remove a migration from the log.
     *
     * @param  object  $migration
     * @return void
     */
    public function delete($migration);

    /**
     * Get the next migration batch number.
     *
     * @param  string  $tenantId
     * @return int
     */
    public function getNextBatchNumber($tenantId);

    /**
     * Create the migration repository data store.
     *
     * @return void
     */
    public function createRepository();

    /**
     * Determine if the migration repository exists.
     *
     * @return bool
     */
    public function repositoryExists();

    /**
     * Set the information source to gather data.
     *
     * @param  string  $name
     * @return void
     */
    public function setSource($name);
}
