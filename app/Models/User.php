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

	public function subuser()
    {
        return $this->hasMany('App\Models\Subuser');
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
		//$user = $this->find('parea16@gmail.com')->subusers();
		//$user = $this->where('email', 'parea16@gmail.com')->subusers->get()->toArray();
		$user = $this::WhereEmail($data['email'])->first();
		if(!$user){
			return response()->json([
				'message' => 'User dont exist',
			], 404);
		}

		$user = $user->toArray();
        $session_array = [
			'id' => $user['id'],
			'name' => $user['name'],
			'is_authenticated' => 1
		];
		session($session_array);
		
		return response()->json([
			'message' => 'Auth is success'
		]);
    }

    public function signIns()
	{
		//$user = $this->find('parea16@gmail.com')->subusers();
		//$user = $this->where('email', 'parea16@gmail.com')->subusers->get()->toArray();
		$user = $this::find(1)->subuser;
        dd($user);
		$cnt = 0;

		foreach ($this->crm_roles as $role) {

			$user['roles'][$cnt] = $role->toArray();

			foreach ($role->crm_permissions as $permission) {
				$user['roles'][$cnt]['permissions'][] = $permission->toArray();
			}
			$cnt++;
		}
        session($user);
    }


}