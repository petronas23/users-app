<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
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