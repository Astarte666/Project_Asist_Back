<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRoleErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Spatie\Permission\Exceptions\UnauthorizedException $e) {
            return response()->json([
                'error' => 'Acceso denegado. No tienes el rol o permiso necesario para esta acción.',
                'detalle' => $e->getMessage(),
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ha ocurrido un error inesperado.',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }
}
