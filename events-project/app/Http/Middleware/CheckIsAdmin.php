<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado e se o user_type_id é '4' (Admin)
        if (Auth::check() && Auth::user()->user_type_id == 4) { 
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
    }
}
