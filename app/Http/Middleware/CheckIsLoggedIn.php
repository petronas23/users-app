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
		$pathArr = explode('/', $requestPath);
		if(isset($pathArr[0]) && $pathArr[0] == 'profile' && Session::get('is_authenticated') !== 1){
			return abort(404);
		}elseif(Session::get('is_authenticated') === 1 && ($requestPath === 'sign-in' || $requestPath === 'sign-up' )){
			return redirect('profile/user-session-info');
		}

		return $next($request);
		
    	// if (Session::get('is_authenticated') === 1) {

		// 	if ($requestPath !== 'sign-in' && $requestPath !== 'sign-up' ) {
		// 		return $next($request);
		// 	}

		// 	return redirect('profile');

    	// } else {
			
		// 	if ($request->path() === 'sign-in' || $requestPath === 'sign-up' || ) {
		// 		return $next($request);
		// 	}
    	// }

		// dd('here');
    	// return abort(404);
        //return $next($request);
    }
}
