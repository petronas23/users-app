<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function registration()
    {
        return view('layouts.registration');
    }

    public function ajaxRegistration(SignUpRequest $request)
    {
        $postData = $request->validated();
        $user = new User;
        
        return $user->createOrFail($request->validated(), ['email' => $postData['email']]);
    } 
}