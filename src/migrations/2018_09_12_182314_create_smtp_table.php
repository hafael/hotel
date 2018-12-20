<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtpTable extends Migration
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
        $smtpConnectionTable = config('hotel.smtp_connection_table', 'smtp_connections');

        Schema::connection($this->getConnection())->create($smtpConnectionTable, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('tenant_id');
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->string('from');
            $table->string('from_name');
            $table->string('reply');
            $table->integer('port');
            $table->string('encryption');
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
        $smtpConnectionTable = config('hotel.smtp_connection_table', 'smtp_connections');

        Schema::connection('system')->dropIfExists($smtpConnectionTable);
    }
}
