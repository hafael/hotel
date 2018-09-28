<?php

namespace Hafael\Hotel\Console;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    use TenantCommandTrait;
    
    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->runForAll();
        } elseif ($tenants = $this->option('tenant')) {
            $this->runFor($this->getValidTenants($tenants));
        } else {
            $this->fire();
        }
    }

    /**
     * Get all of the migration paths.
     *
     * @return array
     */
    protected function getMigrationPaths()
    {
        // Here, we will check to see if a path option has been defined. If it has we will
        // use the path relative to the root of the installation folder so our database
        // migrations may be run for any customized path from within the application.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return collect($this->option('path'))->map(function ($path) {
                return ! $this->usingRealPath()
                                ? $this->laravel->basePath().'/'.$path
                                : $path;
            })->all();
        }

        return array_merge(
            $this->migrator->paths(), [$this->getMigrationPath()]
        );
    }

    /**
     * Determine if the given path(s) are pre-resolved "real" paths.
     *
     * @return bool
     */
    protected function usingRealPath()
    {
        return $this->input->hasOption('realpath') && $this->option('realpath');
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $migrationPath = config('hotel.tenant_migration_path', 'tenant');

        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.$migrationPath;
    }
}
