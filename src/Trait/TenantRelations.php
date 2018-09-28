<?php

namespace Hafael\Hotel\Traits;

trait TenantRelations
{
  public function domain()
  {
    $tenantIdentifier = config('hotel.tenant_class_id');

    return $this->hasOne('\TotalPDV\Negocios\Models\Dominio', 'tenant_id', $tenantIdentifier);
  }

  public function tenant_connection()
  {
    $tenantIdentifier = config('hotel.tenant_class_id');
    
    return $this->hasOne('\TotalPDV\Negocios\Models\Conexao', 'tenant_id', $tenantIdentifier);
  }

  public function migrations()
  {
    $tenantIdentifier = config('hotel.tenant_class_id');

    return $this->hasMany('\TotalPDV\Negocios\Models\Migracao', 'tenant_id', $tenantIdentifier);
  }
}