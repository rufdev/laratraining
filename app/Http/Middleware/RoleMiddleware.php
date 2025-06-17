<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = auth()->user()->role->value; // Get the string value of the enum
        
        \Log::info('User Role:', ['role' => $userRole]);
        \Log::info('Required Roles:', $roles);

        
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
