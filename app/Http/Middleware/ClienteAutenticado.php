<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Libera "Minha conta" só para quem entrou como cliente.
 *
 * Provisório: confere a sessão criada pelo login fictício. Quando existir a
 * tabela de clientes, troque pelo middleware `auth` com um guard próprio.
 */
class ClienteAutenticado
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('cliente')) {
            return to_route('cliente.entrar');
        }

        return $next($request);
    }
}
