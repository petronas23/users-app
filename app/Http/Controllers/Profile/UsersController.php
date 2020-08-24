<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateSubuserRequest;

use App\Models\User;
use App\Models\Subuser;

class UsersController extends Controller
{
    public function __construct(Subuser $subuser)
	{
		$this->subuser = $subuser;
    }
    
    public function index()
    {
        $data = [
            'page' => 'Subuser',
            'cols' => ['id', 'Name', 'Date add', 'action' ]
        ];
        return view('profile.profile', $data);
    }

    public function ajaxDatatable()
    {
        // $users = User::getMany([
		// 	'take' => $request->input('length', $table->config['pageLength']),
		// 	'skip' => $request->input('start', 0),
		// 	'field' => $table->config['cols'][$request->input('order.0.column')-1]['column'],
		// 	'dir' => $request->input('order.0.dir')
        // ]);

        $outputData = [];
        $dbData = $this->subuser->getSubusers(session('id_user'));
       
		foreach ($dbData as $item) {
			$outputData[] = [
                'id' => $item['id'],
                'user_id' => $item['user_id'],
                'name' => $item['name'],
                'created_at' => date('H:i:s Y-m-d', strtotime($item['created_at']))
            ];
        }

        return response()->json([
			'recordsTotal' => $this->subuser->countSubusers(session('id_user')),
			'recordsFiltered' => $this->subuser->countSubusers(session('id_user')),
			'data' => $outputData
		]);
    }
    
   function ajaxAddSubuserModal()
   {
        return view('profile.modalAddSubuser');
   }

   public function ajaxAddSubuser(CreateSubuserRequest $request)
   {
        $postData = $request->validated();
        $subuser = new Subuser;
        $newData = [
            'name' => $postData['subuser_name'],
            'user_id' => session('id_user')
        ];

    return $subuser->createSubuser($newData);
   }
   
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}