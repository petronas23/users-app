<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
//use App\User;

class UsersController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    public function index()
    {
        return view('profile.profile');
    }

    public function authentication()
    {
        return view('layouts.authentication');
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}