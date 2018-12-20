<?php

namespace Hafael\Hotel\Middleware;

use Closure;
use Hafael\Hotel\Models\TenantDomain;
use Hafael\Hotel\Support\TenantConnector;

class TenantMiddleware {

    use TenantConnector;

    /**
     * @var TenantDomain
     */
    protected $domain;

    /**
     * TenantInterceptor constructor.
     * 
     * @param Hafael\Hotel\Models\TenantDomain $domain
     */
    public function __construct(TenantDomain $domain) 
    {
        $this->domain = $domain;
    }

    /**
     * Intercepta a requisição http para obter o subdominio e definir 
     * a conexão do banco de dados da aplicação hospedada no cliente.
     * Usa a conexão mysql/mariadb associada ao dominio.
     * 
     * Caso não encontre, redireciona o usuário para pagina 404.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) 
    {
        
        //Obtem a primeira parte do subdomínio da requisição http
        $subdomain = explode('.', parse_url($request->getSchemeAndHttpHost(), PHP_URL_HOST));
        $subdomain = !empty($subdomain) ? $subdomain[0] : 'dev';
      
        //Busca pelo registro do subdominio na base de clientes
        $domain = $this->domain->where('subdomain', $subdomain)
                                  ->with('tenant.tenant_connection')->first();
        
        if(empty($domain) || 
           empty($domain->tenant) || 
           empty($domain->tenant->tenant_connection)) 
        {
            return abort(404);
        }

        //Define a base de dados da aplicação (no cliente) a partir do domínio
        $this->reconnect($domain->tenant->tenant_connection);

        //Define o driver/conexão de smtp do cliente a partir do dominio
        if(!empty($domain->smtp_connection) && config('hotel.use_tenant_smtp_connection') === true) {
            $this->setSMTPConnection($domain->smtp_connection);
        }

        //Continua a requisição
        return $next($request);
    }
}