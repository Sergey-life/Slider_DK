<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuth
{
    private const AUTH_USER = 'admin';
    private const AUTH_PASS = 'admin';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $hasSuppliedCredentials = !(empty(request()->server('PHP_AUTH_USER')) && empty(request()->server('PHP_AUTH_PW')));
        $isNotAuthenticated = (
            !$hasSuppliedCredentials ||
            request()->server('PHP_AUTH_USER') != self::AUTH_USER ||
            request()->server('PHP_AUTH_PW')   != self::AUTH_PASS
        );
        if ($isNotAuthenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
        return $next($request);
    }
}
