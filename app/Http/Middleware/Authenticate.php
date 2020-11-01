<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //I JUST CREATE THIS AUTH, TO SHOW A SMALL EXAMPLE THAT CAN BE POSSIBLE TO USE< LIKE JWT OS THE TABLE WITH TOKENS ETC....
        $token = $request->bearerToken();
        $bearer_token = config('auth.bearer_token', '');
        if (!$bearer_token || ($token != $bearer_token)) {
            return response('Unauthorized.', 401);
        }
        return $next($request);
    }
}
