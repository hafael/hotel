<?php

namespace Hafael\Hotel\Models;

use Illuminate\Database\Eloquent\Model;

class TenantMigration extends Model
{
    
    /**
     * The default connection schema.
     *
     * @var string
     */
    protected $connection = 'system';

    /**
     * Migration Repository Table.
     *
     * @var string
     */
    protected $table = 'migrations';

    public function __construct()
    {
        $this->table = config('database.migrations');
        $this->connection = config('hotel.system_schema');

        parent::__construct();
    }

    public function tenant()
    {
        $tenantClass = config('hotel.tenant_class');
        $tenantIdColumn = config('hotel.tenant_class_id');

        return $this->belongsTo($tenantClass, 'tenant_id', $tenantIdColumn);
    }

    public function domain()
    {
        $tenantClass = config('hotel.tenant_domain_class');

        return $this->belongsTo($tenantClass, 'tenant_id', 'tenant_id');
    }

}