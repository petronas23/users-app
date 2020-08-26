<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\CreateSubuserRequest;
use App\Http\Requests\EditSubuserRequest;
use App\Http\Requests\RemoveSubuserRequest;

use App\Models\User;
use App\Models\Subuser;
use App\Models\Socials;
use App\Models\UserAuths;

use Session;

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
            'cols' => ['id', 'Name', 'Date add', 'Subuser Socials', 'Action' ]
        ];
        return view('profile.profile', $data);
    }

    public function ajaxDatatable(Request $request)
    {
        $cond = [
            'id_user' => session('id_user'),
            'length' => $request->input('length'),
			'start' => $request->input('start', 0),
        ];
        $outputData = [];
        $dbData = $this->subuser->getSubusers($cond);
       
		foreach ($dbData as $item) {
			$outputData[] = [
                'id' => $item['id'],
                'user_id' => $item['user_id'],
                'name' => $item['name'],
                'created_at' => date('H:i:s Y-m-d', strtotime($item['created_at'])),
                'user_auths' => $item['user_auths'],
            ];
        }

        return response()->json([
			'recordsTotal' => $this->subuser->countSubusers(session('id_user')),
			'recordsFiltered' => $this->subuser->countSubusers(session('id_user')),
			'data' => $outputData
		]);
    }
    
   function ajaxAddSubuserModal($id_subuser='')
   {
       $data=[
            'title' => "Add subuser",
            'action' => url('/profile/subusers/add') 
       ];

       if($id_subuser){
           if(!$subuser = $this->subuser->getSubuser($id_subuser)){
                dd('Subuser dont exist');
           }

            $data = [
                'title' => "Edit subuser",
                'action' => url('/profile/subusers/edit'),
                'subuser' => $subuser
            ];
       }

        return view('profile.modalAddSubuser', $data);
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

   public function ajaxEditSubuser(EditSubuserRequest $request)
   {
        $postData = $request->validated();
        $newData = [
            'name' => $postData['subuser_name'],
        ];
        
        $cond = [
            'user_id' => session('id_user'),
            'id' => $postData['subuser_id']
        ];

        return $this->subuser->editSubuser($newData, $cond);
   }

   public function ajaxRemoveSubuser(RemoveSubuserRequest $request)
   {
        $postData = $request->validated();

        $cond = [
            'user_id' => session('id_user'),
            'id' => $postData['id']
        ];

        return $this->subuser->removeSubuser($cond);
   }

   public function sessionInfoPage()
   {
        return view('profile.sessionInfoPage'); 
   }

   public function modalAttachSocials($id_subuser)
   {
        $data = [
            'socials' => Socials::get()->toArray(),
            'id_subuser' => $id_subuser
        ];

        $userAuth = new UserAuths;
        $userSocials = $userAuth->getUserAuths($id_subuser, 'subuser');
        if($userSocials){
            $userSocialIds = array_column($userSocials, 'auth_id');
            $data['userSocialIds'] = array_flip($userSocialIds);
        }
       
        return view('profile.modalAtachSocials', $data); 
   }

   public function ajaxAttachSocial(Request $request)
   {
        $postData = $request->all();
        $id_subuser = $postData['id_subuser'];
        unset($postData['id_subuser']);


        $socials = Socials::get()->toArray();
        $socialsArr = array_combine(array_column($socials, 'type'), array_column($socials, 'id'));

        $userAuth = new UserAuths;
        $userSocials = $userAuth->getUserAuths($id_subuser, 'subuser');
        //

        if($userSocials){
            $userAuth->where('user_id', $id_subuser)->where('user_type', 'subuser')->delete();  
        }        

        if($postData){
            $insertBatch = [];
            foreach($postData as $key => $social){
                $insertBatch[] = [
                    'auth_id' => $socialsArr[$key],
                    'user_id' => $id_subuser,
                    'user_type' => 'subuser'
                ];
            }
            UserAuths::insert($insertBatch);
        }

        return response()->json([
            'message' => 'Authentications changed with success!',
        ], 201);
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