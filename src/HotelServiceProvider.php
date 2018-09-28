<?php

namespace Hafael\Hotel;

use Illuminate\Support\ServiceProvider;
use Hafael\Hotel\Console\FreshCommand;
use Hafael\Hotel\Console\MigrateCommand;
use Hafael\Hotel\Console\RefreshCommand;
use Hafael\Hotel\Console\ResetCommand;
use Hafael\Hotel\Console\RollbackCommand;
use Hafael\Hotel\Console\StatusCommand;
use Hafael\Hotel\Seeder\Manager;

class HotelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepository();

        // Once we have registered the migrator instance we will go ahead and register
        // all of the migration related commands that are used by the "Artisan" CLI
        // so that they may be easily accessed for registering with the consoles.
        $this->registerMigrator();

        $this->registerSeeder();

        $this->registerCommands();

        $this->publishes([
            __DIR__ . '/config/hotel.php' => config_path('hotel.php')
        ]);
    }
    

    /**
     * Register the migration repository service.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton('migrator.repository', function ($app) {
            $table = $app['config']['database.migrations'];

            return new DatabaseMigrationRepository($app['db'], $table);
        });
    }

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator.instance', function ($app) {
            $repository = $app['migrator.repository'];

            return new Migrator($repository, $app['db'], $app['files']);
        });
    }

    /**
     * Register all of the migration commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $commands = ['Migrate', 'Fresh', 'Rollback', 'Reset', 'Refresh', 'Status'];

        // We'll simply spin through the list of commands that are migration related
        // and register each one of them with an application container. They will
        // be resolved in the Artisan start file and registered on the console.
        foreach ($commands as $command) {
            $this->{'register'.$command.'Command'}();
        }

        // Once the commands are registered in the application IoC container we will
        // register them with the Artisan start event so that these are available
        // when the Artisan application actually starts up and is getting used.
        $this->commands(
            'command.tenantdb', 
            'command.tenantdb.fresh',
            'command.tenantdb.rollback',
            'command.tenantdb.reset',
            'command.tenantdb.refresh',
            'command.tenantdb.status'
        );
    }


    /**
     * Register the "refresh" migration command.
     *
     * @return void
     */
    protected function registerFreshCommand()
    {
        $this->app->singleton('command.tenantdb.fresh', function () {
            return new FreshCommand();
        });
    }

    /**
     * Register the "migrate" migration command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.tenantdb', function ($app) {
            return new MigrateCommand($app['migrator.instance']);
        });
    }

    /**
     * Register the "rollback" migration command.
     *
     * @return void
     */
    protected function registerRollbackCommand()
    {
        $this->app->singleton('command.tenantdb.rollback', function ($app) {
            return new RollbackCommand($app['migrator.instance']);
        });
    }

    /**
     * Register the "reset" migration command.
     *
     * @return void
     */
    protected function registerResetCommand()
    {
        $this->app->singleton('command.tenantdb.reset', function ($app) {
            return new ResetCommand($app['migrator.instance']);
        });
    }

    /**
     * Register the "refresh" migration command.
     *
     * @return void
     */
    protected function registerRefreshCommand()
    {
        $this->app->singleton('command.tenantdb.refresh', function () {
            return new RefreshCommand();
        });
    }

    

    /**
     * Register the "status" migration command.
     *
     * @return void
     */
    protected function registerStatusCommand()
    {
        $this->app->singleton('command.tenantdb.status', function ($app) {
            return new StatusCommand($app['migrator.instance']);
        });
    }

    

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'migrator.instance', 
            'migrator.repository', 
            'command.tenantdb',
            'command.tenantdb.rollback', 
            'command.tenantdb.reset',
            'command.tenantdb.refresh',
            'command.tenantdb.status', 
            'migrator.creator',
        ];
    }

    protected function registerSeeder()
    {
        $this->app->singleton('tenantdb.seeder.manager', function () {
            return new Manager();
        });
    }
}
