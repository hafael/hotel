<?php
namespace Migrator\Seeder;

use Illuminate\Database\Seeder as IlluminateDBSeeder;

/**
 * Class DatabaseSeeder.
 */
class DatabaseSeeder extends IlluminateDBSeeder
{
    public function run()
    {
        $seeders = app('migrator.seeder.manager')->getSeeders();
        $seeders->each(function ($seeder) {
            $this->call($seeder);
        });
    }
}