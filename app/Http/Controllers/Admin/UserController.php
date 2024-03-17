<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|string
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'roleID' => 'required'
        ]);

        $userExist  = User::query()->where('email','=',$request['email'])->get();
        if(count($userExist) > 0 ){
            $response['status'] = 'failed';
            $response['message'] = 'Email Already Exist please try another Email';
        }else if(!filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
            $response['status'] = 'failed';
            $response['message'] = 'Enter a Valid Email Address';
        }else{
            $user = new User();
            $user->name = $request['name'];
            $user->phone = $request['phone'];
            $user->email = $request['email'];
            $user->password = bcrypt($request['password']);
            $user->role_id = $request['roleID'];
            $result = $user->save();
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Successfully Add User';
            } else {
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to Add User';
            }
        }

        return json_encode($response);
        die();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $user = DB::table('users')
            ->select('users.*', 'roles.name as roleName')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id','=',$id)->get()->first();
        return json_encode($user);
        die();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return false|string
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'roleID' => 'required'
        ]);

            $user =  User::find($id);
            $user->name = $request['name'];
            $user->phone = $request['phone'];
            if($request['password'] !=''){
                $user->password = bcrypt($request['password']);
            }
            $user->role_id = $request['roleID'];
            $result = $user->save();
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Successfully Update User';
            } else {
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to Update User';
            }


        return json_encode($response);
        die();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return false|string
     */
    public function destroy($id)
    {
        $result =  User::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete User';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete User';
        }
        return json_encode($response);
        die();
    }

    public function users()
    {
        $users['data'] = DB::table('users')
            ->select('users.*', 'roles.name as roleName')
            ->join('roles', 'users.role_id', '=', 'roles.id')->get();
        return json_encode($users);
    }

    public function role(Request $request)
    {
        if (isset($request['query'])) {
            $roles = Role::query()->where('name', 'like', '%' . $request['query'] . '%')->get();
        } else {
            $roles = Role::query()->get();
        }
        $role = array();
        foreach ($roles as $item) {
            $role[] = array(
                "id" => $item['id'],
                "text" => $item['name']
            );
        }
        return json_encode($role);
        die();
    }
    public function status(Request $request)
    {
        $user = User::find($request['id']);
        $user->status = $request['status'];
        $result = $user->save();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to '.$request['status'];
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status '.$request['status'];
        }
        return json_encode($response);
        die();
    }
}
