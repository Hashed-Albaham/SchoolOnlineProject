<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * [v8.0] Super Admin Only Middleware
 * Restricts access to routes that only Super Admins can access.
 */
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->isSuperAdmin()) {
            abort(403, __('site.super_admin_only'));
        }

        return $next($request);
    }
}
