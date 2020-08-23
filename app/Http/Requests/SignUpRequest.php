<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6|max:100',
        ];
    }
}