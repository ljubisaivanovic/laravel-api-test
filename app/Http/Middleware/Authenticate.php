<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('token_key')) {
            return redirect()->route('login');
        }
        $client = new Client();

        if (!$client->checkTokenExpirationTime(session()->get('refresh_token_key'))) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
