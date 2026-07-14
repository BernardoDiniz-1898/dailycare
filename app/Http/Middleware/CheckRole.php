<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user() || !$request->user()->ativo) {
            abort(403);
        }

        if (!empty($roles) && !in_array($request->user()->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
