<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     * Принимает список допустимых role_id через параметры middleware.
     * Пример использования: ->middleware('role:1,2')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        // Если пользователь не аутентифицирован или его роль не разрешена – 403
        if (!$user || (! empty($roles) && ! in_array($user->role_id, $roles))) {
            abort(403);
        }

        return $next($request);
    }
} 