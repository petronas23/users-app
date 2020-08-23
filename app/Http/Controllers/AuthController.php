<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\SignInRequest;

use App\Models\User;
use Session;


class AuthController extends Controller
{
    public function authentication()
    {
        return view('layouts.authentication');
    }
    public function ajaxAuthentication(SignInRequest $request)
    {
        $userObj = new User;
        return $user = $userObj->signIn($request->validated());		
    }

    public function ajaxLogout()
	{
		Session::flush();
		return response()->json([
			'message' => 'You have successfully logged out!',
		]);
	}
}