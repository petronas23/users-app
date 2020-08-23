<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Socials extends Model
{
    // protected $fillable = [
    //     'name', 'email', 'password',
	// ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
	
}