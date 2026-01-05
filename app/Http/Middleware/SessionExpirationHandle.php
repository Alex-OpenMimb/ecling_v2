<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class SessionExpirationHandle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        DB::statement("
                UPDATE users
                SET session_id = NULL,
                    last_session = NULL
                WHERE last_session IS NOT NULL
                  AND last_session < NOW() - INTERVAL 24 HOUR
            ");
        return $next($request);
    }
}
