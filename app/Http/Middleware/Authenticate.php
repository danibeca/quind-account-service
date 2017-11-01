<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $result = false;

        try
        {
            $token = explode(' ', $request->header('Authorization'))[1];
            $token = (new Parser())->parse((string) $token);
            $public = file_get_contents(realpath('../storage/oauth-public.key'));
            $signer = new Sha256();
            $result = $token->verify($signer, $public);
        } catch (\Exception $e)
        {
            return response('Unauthorized.', 401);
        }
        if ($result === false)
        {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}