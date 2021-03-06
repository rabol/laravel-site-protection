<?php

namespace Rabol\SiteProtection\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Arr;

class SiteProtection
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $password = config('site-protection.passwords');
        $cookieLifeTime = config('site-protection.cookie_life_time',0);
        if($cookieLifeTime)
            $cookieLifeTime = time() + $cookieLifeTime;

        if (empty($password)) {
            return $next($request);
        }

        $passwords = explode(',', $password);

        if (in_array($request->get('site-password-protected'), $passwords)) {
            setcookie('site-password-protected', encrypt($request->get('site-password-protected')), $cookieLifeTime, '/');
            return redirect($request->url());
        }

        try {
            $usersPassword = decrypt(Arr::get($_COOKIE, 'site-password-protected'));
            if (in_array($usersPassword, $passwords)) {
                return $next($request);
            }
        } catch (DecryptException $e) {
            // empty value in cookie
        }

        return response(view('site-protection::site-protection-form'), 403);
    }
}
