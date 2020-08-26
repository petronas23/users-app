<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\SignInRequest;
use Laravel\Socialite\Facades\Socialite;

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
        $request->validated();

        $userObj = new User;
        return $user = $userObj->signIn($request->all());		
    }

    public function ajaxLogout()
	{
		Session::flush();
		return response()->json([
			'message' => 'You have successfully logged out!',
		]);
    }

    public function getUsersByEmail(Request $reques)
    {
        $post = $reques->all();

        $user = User::getUsers($post['email']);
        if(!$user){
            return '';
        }
        
        return response()->json($user);
    }
    
    public function redirectToProvider(Request $reques, $type='', $id= '', $user_type='')
    {
        Session::flush();
        $session = [
            'user_type' => $user_type,
			'id_user' => $id
        ];
        session($session);
        return Socialite::driver($type)->redirect();
    }

    public function handleProviderCallback(Request $reques, $type='')
    {
        $user = Socialite::driver($type)->user();

        $session = $user->user;
        $session['is_authenticated'] = 1;
        $session['social'] = $type;

        session($session);

        return redirect('profile/user-session-info');
    }
}