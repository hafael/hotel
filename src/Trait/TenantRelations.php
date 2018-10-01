<?php

namespace Hafael\Hotel\Traits;

trait TenantRelations
{
  public function domain()
  {
    $tenantDomainClass = config('hotel.tenant_domain_class'); 
    $tenantIdentifier = config('hotel.tenant_class_id');

    return $this->hasOne($tenantDomainClass, 'tenant_id', $tenantIdentifier);
  }

  public function tenant_connection()
  {
    $tenantIdentifier = config('hotel.tenant_class_id');
    $tenantConnectionClass = config('hotel.tenant_domain_class');
    
    return $this->hasOne($tenantConnectionClass, 'tenant_id', $tenantIdentifier);
  }

  public function migrations()
  {
    $tenantIdentifier = config('hotel.tenant_class_id');
    $tenantMigrationClass = config('hotel.tenant_migration_class');

    return $this->hasMany($tenantMigrationClass, 'tenant_id', $tenantIdentifier);
  }
}