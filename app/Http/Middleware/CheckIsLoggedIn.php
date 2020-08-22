<?php

namespace App\Http\Middleware;

use Closure, Session, Config;

class CheckIsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public function isAuth()
	{
		if (Session::get('is_authenticated') === 1) {
			return true;
		}
		return false;
	}
	
    public function handle($request, Closure $next)
    {
        $requestPath = $request->path();
		
    	if ($this->isAuth()) {

			if ($requestPath !== 'sign-in' && $requestPath !== 'sign-up') {
				return $next($request);
			}

			return redirect('profile/users');

    	} else {

            //Config::set('adminlte.plugins', []);

			if ($request->path() === 'sign-in' || $requestPath === 'crm/sign-up') {
				return $next($request);
			}
    	}

		dd('here');
    	return abort(404);
        //return $next($request);
    }
}
