<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tenant Model Class
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'tenant_class' => '\App\User',
    'tenant_class_id' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Tenant Domain Model Class
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'tenant_domain_class' => '\Hafael\Hotel\Models\TenantDomain',
    
    /*
    |--------------------------------------------------------------------------
    | Tenant Connection Model Class
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'tenant_connection_class' => '\Hafael\Hotel\Models\TenantConnection',
    
    //Tenant identifier
    'tenant_id'  => 'tenant_id', 
    //DB Hostname
    'tenant_host_column'      => 'host',
    //DB Name
    'tenant_database_column'  => 'database',
    //MySQL Username with r-w-x grants
    'tenant_username_column'  => 'username',
    //MySQL Password
    'tenant_password_column'  => 'password',

    /*
    |--------------------------------------------------------------------------
    | Tenant Schema
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'tenant_schema' => 'tenant',

    /*
    |--------------------------------------------------------------------------
    | System Schema
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'system_schema' => 'system',

    /*
    |--------------------------------------------------------------------------
    | Tenant Domain Table
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'domain_table' => 'domains',

    /*
    |--------------------------------------------------------------------------
    | Tenant Connection Table
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'connection_table' => 'connections',


    /*
    |--------------------------------------------------------------------------
    | Tenant Migration`s Path
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'tenant_migration_path' => 'tenant',

    /*
    |--------------------------------------------------------------------------
    | Tenant Migration DB Schema Builder Type
    |--------------------------------------------------------------------------
    |
    | Initial Epoch is a number in timestamp format (unix timestamp * 1000)
    | used by LaraFlake package to determine the start time of their
    | application for the creation of IDs in 64bit format.
    |
    */
    'tenant_id_migration_type' => 'integer',

    /*
    |--------------------------------------------------------------------------
    | Tenant Class Attributes
    |--------------------------------------------------------------------------
    |
    | The provider informs which driver will be used to obtain the database
    | node identification.
    | Available: "local" and "database".
    |
    */
    //
    'migration_table_tenant_id_column'  => 'tenant_id',

    
   

];