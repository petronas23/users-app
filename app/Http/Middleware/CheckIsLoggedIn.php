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
	
    public function handle($request, Closure $next)
    {
		// Session::flush();
		//dd(Session::all());
		//Session::flush();
        $requestPath = $request->path();
		
    	if (Session::get('is_authenticated') === 1) {

			if ($requestPath !== 'sign-in' && $requestPath !== 'sign-up') {
				return $next($request);
			}

			return redirect('profile');

    	} else {

            //Config::set('adminlte.plugins', []);

			if ($request->path() === 'sign-in' || $requestPath === 'sign-up') {
				return $next($request);
			}
    	}

		//dd('here');
    	return abort(404);
        //return $next($request);
    }
}
