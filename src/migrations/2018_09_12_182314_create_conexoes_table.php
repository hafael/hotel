<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConexoesTable extends Migration
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
        $connectionTable = config('hotel.connection_table', 'connections');

        Schema::connection($this->getConnection())->create($connectionTable, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('tenant_id');
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->string('database');
            $table->integer('port');
            $table->boolean('ssl')->default(0);
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
        $connectionTable = config('hotel.connection_table', 'connections');

        Schema::connection('system')->dropIfExists($connectionTable);
    }
}
