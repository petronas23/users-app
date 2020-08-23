<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subuser extends Model
{
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function subuser()
    {
        return $this->belongsTo('App\Models\Subuser');
    }
	
}