<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Libraries\Install\InstallHandler;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (InstallHandler::checkInstallation()) {
            return $next($request);
        }
        return redirect()->route('install');
    }
}
