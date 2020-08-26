<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserAuths extends Model
{
    protected $table = 'users_auths';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function subuser()
    {
        return $this->belongsTo('App\Models\Subuser');
    }

    public function socials()
    {
        return $this->hasMany('App\Models\Socials');
    }

   public static function getUserAuths($id, $type)
   {
        return UserAuths::where('user_id', $id)
                        ->where('user_type', $type)
                        ->get()->toArray();
    }

   public static function removeUserSocials($id, $type)
   {
    return UserAuths::where('user_id', $id)
                    ->where('user_type', $type)
                    ->delete();
   }

   public static function updateUserSocials($id, $type)
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
	
}

