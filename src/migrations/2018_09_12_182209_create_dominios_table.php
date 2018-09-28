<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDominiosTable extends Migration
{
    protected $connection = 'system';

    public function __construct()
    {
        $this->connection = config('hotel.system_schema', 'system');
        parent::__construct();
    }
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $domainTable = config('hotel.domain_table', 'domains');

        Schema::connection($this->getConnection())->create($domainTable, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('domain');
            $table->string('subdomain');
            $table->string('description');
            $table->string('tenant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $domainTable = config('hotel.domain_table', 'domains');

        Schema::connection($this->getConnection())->dropIfExists($domainTable);
    }
}
