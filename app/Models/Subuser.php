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

	public function userAuths()
    {
        return $this->hasMany('App\Models\UserAuths','user_id', 'id');
    }
	
	public function socials()
    {
        return $this->hasMany('App\Models\AuthType');
    }

    public function scopeStart($query, $start)
	{ 
		return $query->offset($start );
	}

	public function scopeLength($query, $length)
	{
		return $query->limit($length);
	}

	public function scopeWhereUserId($query, $userId)
	{
		return $query->where('user_id', $userId);
	}
	public function scopeWhereId($query, $id)
	{
		return $query->where('id', $id);
	}

	public function getSubuserSocial($ids)
	{
		$users = DB::table('users_auths')
			->whereIn('user_id', $ids)
			->where('type', 'subuser')
			->leftJoin('auth_types', 'users_auths.auth_id', '=', 'auth_types.id')
            ->get();
	}

	public function getSubuser($id_subuser)
	{
		if(!$subuser = Subuser::find($id_subuser)){
			return false;
		}

		return $subuser->toArray();
	}
	
    public function getSubusers($cond)
    {
		$default = array_merge([
			'start' => 0,
			'length' => 10,
			'field' => 'id',
			'dir' => 'desc'
		], $cond);

		return $users = Subuser::with(['userAuths' => function ($query) {
						$query->where('user_type', 'subuser')->leftJoin('auth_types', 'users_auths.auth_id', '=', 'auth_types.id');
					}])
					->WhereUserId($cond['id_user'])
					->limit($default['length'])
					->start($default['start'])
					->orderBy($default['field'], $default['dir'])
					->get()->toArray();
	}
	
	public function getAllSubusers()
	{
		return $users = Subuser::with(['userAuths' => function ($query) {
					$query->where('user_type', 'subuser')->leftJoin('auth_types', 'users_auths.auth_id', '=', 'auth_types.id');
				}])
				->WhereUserId($cond['id_user'])
				->get()->toArray();
	}

    public function countSubusers($userId)
	{
		return $this->whereUserId($userId)->count();
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
	
	public function editSubuser($new_data, $conditions)
	{
		if(!$this->whereId($conditions['id'])->whereUserId($conditions['user_id'])->exists()){
			return response()->json([
				'message' => 'Subuser you try to edit does not exist',
			], 404 );
		}

		if($this->where('id', $conditions['id'])->update($new_data)){
		    return response()->json([
		        'message' => 'Subuser edited with success!',
		    ], 201);
		}

		return response()->json([
			'message' => 'Internal Server Error!',
		], 500);
	}

	public function removeSubuser($conditions)
	{
		if(!$this->whereId($conditions['id'])->whereUserId($conditions['user_id'])->exists()){
			return response()->json([
				'message' => 'Subuser you try to remove does not exist',
			], 404 );
		}

		UserAuths::removeUserSocials($conditions['id'], 'subuser');
		if($this->where('id', $conditions['id'])->delete()){
		    return response()->json([
		        'message' => 'Subuser removed with success!',
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