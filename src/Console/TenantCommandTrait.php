<?php

namespace Hafael\Hotel\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

trait TenantCommandTrait
{
  /**
   * Set tenant.
   *
   * @param array $tenant
   */
  protected function connectUsingTenant(array $tenant)
  {
   
    $tenantSchema = config('hotel.tenant_schema');
    
    Config::set('database.connections.'.$tenantSchema.'.host', $tenant[config('hotel.tenant_host_column')]);
    Config::set('database.connections.'.$tenantSchema.'.database', $tenant[config('hotel.tenant_database_column')]);
    Config::set('database.connections.'.$tenantSchema.'.username', $tenant[config('hotel.tenant_username_column')]);
    Config::set('database.connections.'.$tenantSchema.'.password', $tenant[config('hotel.tenant_password_column')]);
    
    if(!empty($tenant[config('hotel.tenant_relation_column')]))
    {
        Config::set('database.connections.'.$tenantSchema.'.tenant_id', $tenant[config('hotel.tenant_relation_column')]);
    }

    $this->getLaravel()['db']->purge($tenantSchema);
    // \DB::purge('tenant');
    // \DB::reconect('tenant');
  }
  
  /**
   * Get valid tenants from option.
   *
   * @param string $tenants
   *
   * @return array
   */
  protected function getValidTenants(string $tenants)
  {
    $tenants = explode(',', $tenants);

    $tenantClass = app()->make(config('hotel.tenant_connection_class'));

    $relationColumn = config('hotel.tenant_relation_column');

    $columns = [
      config('hotel.tenant_relation_column'),
      config('hotel.tenant_host_column'),
      config('hotel.tenant_database_column'),
      config('hotel.tenant_username_column'),
      config('hotel.tenant_password_column'),
    ];

    $tenants = $tenantClass::whereIn($relationColumn, $tenants)->get($columns);

    return $tenants->toArray();
  }
  
  /**
   * Run migrations in given tenants.
   *
   * @param array $tenants
   *
   * @return void
   */
  protected function runFor(array $tenants)
  {
    $defaultConnection = config('database.connections.system');

    $relationColumn = config('hotel.tenant_relation_column');

    foreach ($tenants as $tenant) {

      $this->connectUsingTenant($tenant);
      $this->comment("\nTenant: " . $tenant[$relationColumn]);

      $this->fire($tenant[$relationColumn]);
    }

    // Reset tenant database.
    $this->connectUsingTenant($defaultConnection);
  }

  /**
   * Run migrations in given tenants.
   *
   *
   * @return void
   */
  protected function runForAll()
  {
    $columns = [
      config('hotel.tenant_relation_column'),
      config('hotel.tenant_host_column'),
      config('hotel.tenant_database_column'),
      config('hotel.tenant_username_column'),
      config('hotel.tenant_password_column'),
    ];

    $tenantClass = app()->make(config('hotel.tenant_connection_class'));

    $tenants = $tenantClass::all($columns)->toArray();

    $this->runFor($tenants);
  }
}