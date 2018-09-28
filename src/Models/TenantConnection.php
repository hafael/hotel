<?php

namespace Hafael\Hotel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TenantConnection extends Model
{
    
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'system';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'connections';


    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'host',
        'username',
        'password',
        'database',
        'port',
        'ssl',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ssl' => 'boolean'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $timestamps = true;

    /**
     * The attributes that are represented as dates
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function __construct()
    {
        $this->table = config('hotel.connection_table');
        $this->connection = config('hotel.system_schema');

        parent::__construct();
    }

    public function tenant()
    {
        $tenantConnectionClass = config('hotel.tenant_connection_class');
        $tenantIdColumn = config('hotel.tenant_class_id');

        return $this->belongsTo($tenantConnectionClass, 'tenant_id', $tenantIdColumn);
    }

    public function domain()
    {
        $tenantDomainClass = config('hotel.tenant_domain_class');

        return $this->belongsTo($tenantDomainClass, 'tenant_id', 'tenant_id');
    }

    public function migrations()
    {
        $tenantMigrationClass = config('hotel.tenant_migration_class');

        return $this->hasMany($tenantMigrationClass, 'tenant_id', 'tenant_id');
    }
    

}