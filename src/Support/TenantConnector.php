<?php

namespace Hafael\Hotel\Support;

use Illuminate\Support\Facades\DB;
use Hafael\Hotel\Models\TenantConnection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Hafael\Hotel\Models\TenantSmtpConnection;

trait TenantConnector {
   
   /**
    * Habilita o metodo para reconectar o banco de dados da aplicação
    * fornecida através de uma instância via parametro
    * @param Hafael\Hotel\Models\TenantConnection $conexao
    * @return void
    * @throws
    */
   public function reconnect(TenantConnection $connection) 
   {     
      $tenantSchema = config('hotel.tenant_schema', 'tenant');

      //Desconecta do esquema e limpa o cache.
      DB::purge($tenantSchema);
      
      //Define as variaveis da configuração do esquema de conexão mysql/mariadb.
      Config::set('database.connections.'.$tenantSchema.'.host', $connection->host);
      Config::set('database.connections.'.$tenantSchema.'.database', $connection->database);
      Config::set('database.connections.'.$tenantSchema.'.username', $connection->username);
      Config::set('database.connections.'.$tenantSchema.'.password', $connection->password);
      
      // Faz um ping para testar a conexão e retornar erro em caso de falha
      // com a nova conexão.
      Schema::connection($tenantSchema)->getConnection()->reconnect();

   }

   /**
    * Habilita o metodo para reconectar o banco de dados da aplicação
    * fornecida através de uma instância via parametro
    * @param Hafael\Hotel\Models\TenantConnection $conexao
    * @return void
    * @throws
    */
    public function setSMTPConnection(TenantSmtpConnection $smtp) 
    {     
       
      //Define as variaveis da conexão SMTP.
      Config::set('mail.host', $smtp->host);
      Config::set('mail.port', $smtp->port);
      Config::set('mail.username', $smtp->username);
      Config::set('mail.password', $smtp->password);
      Config::set('mail.encryption', $smtp->encryption);
      //Config::set('mail.reply.address', $smtp->reply);
      Config::set('mail.from.address', $smtp->from);
      Config::set('mail.from.name', $smtp->from_name);
       
    }
   
}