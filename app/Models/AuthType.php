<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AuthType extends Model
{
    protected $table = 'auth_types';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
}

