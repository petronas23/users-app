<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Socials extends Model
{
    protected $table = 'auth_types';

    public function usersAuths()
    {
        return $this->belongsTo('App\Models\UserAuths');
    }
	
}