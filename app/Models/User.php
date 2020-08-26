<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name', 'email', 'password',
	];

	public static function scopeWhereEmail($query, $email)
	{
		return $query->where('email', $email);
	}

	public function scopeWhereId($query, $id)
	{
		return $query->where('id', $id);
	}

	public function subuser()
    {
        return $this->hasMany('App\Models\Subuser');
	}
	
	public function userAuths()
    {
        return $this->hasMany('App\Models\UserAuths','user_id', 'id');
    }
	
	public function createOrFail($new_data, $conditions)
	{
		if($this->where($conditions)->exists()){
			return response()->json([
				'message' => 'User with inserted email, already exist',
			], 409 );
		}

		if($this->insert($new_data)){
		    return response()->json([
		        'message' => 'User added with success!',
		    ], 201);
		}

		return response()->json([
			'message' => 'Internal Server Error!',
		], 500);
	}

	public function signIn($data)
	{
		$user = $this::WhereEmail($data['email'])->first();
		if(!$user){
			return response()->json([
				'message' => 'User dont exist',
			], 404);
		}

		$user = $user->toArray();
        $session_array = [
			//'id_user' => $user['id'],
			'name' => $user['name'],
			'is_authenticated' => 1
		];
		if(isset($data['user'])){
			$session_array['user_type'] = 'user';
			$session_array['id_user'] = $data['user'];
		}else{
			$session_array['user_type'] = 'user';
			$session_array['id_user'] = $data['subuser'];
		}
		session($session_array);
		
		return response()->json([
			'message' => 'Auth is success'
		]);
	}
	
	public static function getUsers($email){

		if(!$user = User::where('email', $email)->first()){
			return '';
		}
		$user = $user->toArray();
		$user = User::with(['userAuths' => function ($query) {
						$query->where('user_type', 'user')->leftJoin('auth_types', 'users_auths.auth_id', '=', 'auth_types.id');
					}])->WhereId($user['id'])->first()->toArray();

		
		$user['subusers'] = Subuser::with(['userAuths' => function ($query) {
			$query->where('user_type', 'subuser')->leftJoin('auth_types', 'users_auths.auth_id', '=', 'auth_types.id');
		}])
		->WhereUserId($user['id'])
		->get()->toArray();
		

		return $user;
	}
}