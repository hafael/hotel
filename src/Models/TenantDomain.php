<?php

namespace Hafael\Hotel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TenantDomain extends Model
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
    protected $table = 'domains';


    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'domain',
        'subdomain',
        'description',
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
        $this->table = config('hotel.domain_table');
        $this->connection = config('hotel.system_schema');

        parent::__construct();
    }

    public function tenant()
    {
        $tenantClass = config('hotel.tenant_class');
        $tenantIdColumn = config('hotel.tenant_class_id');

        return $this->belongsTo($tenantClass, 'tenant_id', $tenantIdColumn);
    }

    public function tenant_connection()
    {
        $connectionClass = config('hotel.tenant_connection_class');
        return $this->hasOne($connectionClass, 'tenant_id', 'tenant_id');
    }

}