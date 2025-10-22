<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * En APIs, devolvemos JSON en lugar de redirigir al login.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            abort(response()->json(['message' => 'No autenticado'], 401));
        }
    }
}
