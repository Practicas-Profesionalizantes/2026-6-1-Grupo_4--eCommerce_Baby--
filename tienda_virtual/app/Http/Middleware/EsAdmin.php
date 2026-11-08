<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();

        if (!$usuario || (int) $usuario->Id_rol !== 2) {
            if ($request->expectsJson()) {
                return response()->json(['mensaje' => 'No autorizado'], 403);
            }

            abort(403, 'No tenés permiso para entrar acá.');
        }

        return $next($request);
    }
}