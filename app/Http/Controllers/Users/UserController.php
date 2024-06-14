<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::get();
        return view('admin.users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userGroups = UserGroup::get();
        return view('admin.users.create', compact('userGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $i = $request->all();
        $this->validate($request, [
            'email' => 'required|unique:users|string',
            'name' => 'required|unique:users|string',
        ]);
        $i['password'] = Hash::make($request['password']);
    
          if(User::create($i)){
            return redirect()->back()->with('success', 'User Successfully Added', ['i']);
          }else{
            return redirect()->back()->with('error','There is something wrong please try again!');
          }
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getUsers(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value


        // Total records
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')
                                    ->where('name', 'like', '%' .$searchValue . '%')
                                    ->orWhere('email', 'like', '%' .$searchValue . '%')
                                    ->count();

        
        
        // Fetch records
        $records = User::with('Usergroups')->orderBy($columnName,$columnSortOrder)
        ->where('name', 'like', '%' .$searchValue . '%')
        ->orWhere('email', 'like', '%' .$searchValue . '%')
        ->skip($start)
        ->take($rowperpage)
        ->get();

       

        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as $record){
            $id = $record->id;
            $name = $record->name;
            $email = $record->email;
            $group = $record->Usergroups->name;
            $groupID = $record->group_id;
            $roleID = $record['role'];
            if($record['role'] == 0){
                $role = 'Super Admin';
            }if($record['role'] == 1){
                $role = 'Admin';
            }if($record['role'] == 2){
                $role = 'User';
            }
            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "email" => $email,
                "group_id" => $group,
                "role" => $role,
                "groupId" => $groupID,
                "roleId" => $roleID
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 

        

        echo json_encode($response);
        exit;


    }
    public function getAllGroup(Request $request)
    {
        $records = UserGroup::all();
        echo json_encode($records);
        exit;
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        if($user){
        $destroy = $user->forceDelete($id);

        return redirect()->back()->with(['success' => 'User has been removed successfully.']);
        }
        else{
            return redirect()->back()->withErrors(['User associated with submitted id is missing.']);
        }
    }
    public function UpdateUser(Request $request)
    {
        
        // Check input
        $this->validate($request, [
            'name' => 'required|unique:users,name,'.$request->input('user-id'),
            'email' => 'required|unique:users,email,'.$request->input('user-id'),
        ]);

        $user = User::find($request->input('user-id'));
        $user->name = trim($request->input('name'));
        $user->email = trim($request->input('email'));
        $user['role'] = trim($request->input('role'));
        $user->group_id = trim($request->input('group_id'));
        $user->updated_by = trim(Auth::user()->name);
        $user->save();
        

        $userId = User::find($user->id);

        return redirect()->back()->with(['success' => 'User has been updated successfully.']);

    }

}
