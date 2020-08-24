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

    public static function scopeWhereUserId($query, $userId)
	{
		return $query->where('user_id', $userId);
	}

    public function getSubusers($userId)
    {
        return $this->WhereUserId($userId)->get()->toArray();
    }

    public function countSubusers($userId)
	{
		return $this->WhereUserId($userId)->count();
    }

    public function createSubuser($new_data)
	{
		if($this->insert($new_data)){
		    return response()->json([
		        'message' => 'Subuser added with success!',
		    ], 201);
		}

		return response()->json([
			'message' => 'Internal Server Error!',
		], 500);
    }
    
    public static function scopeGetMany($query, $conditions)
	{
		$expect = array_merge([
			'skip' => 0,
			'take' => 10,
			'field' => 'id',
			'dir' => 'desc'
			//'by_id' => 'desc'
		], $conditions);

		return $query->takeRecords($expect['take'])
					->skipRecords($expect['skip'])
					->with('roles.permissions')
					->orderBy($expect['field'], $expect['dir'])
					//->orderById($expect['by_id'])
					->get();
	}
	
}