<?php

namespace Hafael\Hotel\Console;

use Hafael\Hotel\Migrator;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\ConfirmableTrait;

class MigrateCommand extends BaseCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenantdb {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--path= : The path to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--step : Force the migrations to be run so they can be rolled back individually}
                {--all : Run migrations in all available tenants}
                {--tenant= : Run migrations in given tenants}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';

    /**
     * The migrator instance.
     *
     * @var \Hafael\Hotel\Migrator
     */
    protected $migrator;

    /**
     * Create a new migration command instance.
     *
     * @param  \Hafael\Hotel\Migrator  $migrator
     * @return void
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    /**
     * Execute the console command.
     * @param string $tenantId
     * @return void
     */
    public function fire($tenantId = null)
    {
        
        if (! $this->confirmToProceed()) {
            return;
        }

        $this->prepareDatabase($tenantId);

        // Next, we will check to see if a path option has been defined. If it has
        // we will use the path relative to the root of this installation folder
        // so that migrations may be run for any path within the applications.
        $this->migrator->setOutput($this->output)
                ->run($this->getMigrationPaths(), [
                    'pretend' => $this->option('pretend'),
                    'step' => $this->option('step'),
                ], $tenantId);

        // Finally, if the "seed" option has been given, we will re-run the database
        // seed task to re-populate the database, which is convenient when adding
        // a migration and a seed at the same time, as it is only this command.
        if ($this->option('seed')) {
            $this->call('db:seed', ['--force' => true]);
        }
    }

    /**
     * Prepare the migration database for running.
     *
     * @param string $tenantId
     * @return void
     */
    protected function prepareDatabase($tenantId = null)
    {
        
        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            $this->call(
                'migrate:install', ['--database' => $this->option('database')]
            );
        }
        
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', null, InputOption::VALUE_NONE, 'Run migrations in all available tenants'],

            ['tenant', null, InputOption::VALUE_OPTIONAL, 'Run migrations in given tenants'],
        ];
    }

}

