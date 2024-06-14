<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use App\Models\Mask;
use Auth;

use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = UserGroup::withTrashed()->paginate('10');
        $masks = Mask::where('deleted_at', NULL)->get();
        // $mask_exps = explode(',', $data->mask);
        return view('admin.userGroup.create', compact('data', 'masks'));
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
        $masks = $request->mask;
        foreach($masks as $mak){
            $data[] = $mak;
        }

        $masks_impl = implode(',', $data);
        $i['mask'] = $masks_impl;

        $this->validate($request, [
            'name' => 'required|unique:user_groups|string',
            'mask' => 'required',
        ]);
        $i['created_by'] = Auth::user()->name;
          if(UserGroup::create($i)){
            return redirect('user_groups/create')->with('success', 'User Groups Successfully Added', ['i']);
          }else{
            return redirect('user_groups/create')->with('error','There is something wrong please try again!');
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
      $data = UserGroup::findOrFail($id);
      $masks = Mask::where('deleted_at', NULL)->get();
      $mask_exps = explode(',', $data->mask);
      return view('admin.userGroup.edit', compact('data', 'masks','mask_exps'));
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
        $i = UserGroup::findOrFail($id);
        $u = $request->all();
        $i['updated_by'] = Auth::user()->name;
        $masks = $request->mask;
        foreach($masks as $mask){
            $data[] = $mask;
        }

        $mask_imp = implode(',', $data);
        $u['mask'] = $mask_imp;
        
        $this->validate($request, [
            'name' => 'required|unique:user_groups,name,'.$id,
            'mask' => 'required',
        ]);
        $i->update($u);

        if($i){
          return redirect('user_groups/create')->with('success', 'User Groups Successfully Updated');
        }else{
          return redirect('user_groups/create')->with('error', 'There is something wrong Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userGroups = UserGroup::find($id);
        if ($userGroups->delete()){
            return redirect('user_groups/create')->with('success',"Record Delete Successfully");
        }else {
            return redirect('user_groups/create')->with('error',"There is something wrong please try again!");
        }
    }
    public function restore(Request $request, $id){
        $userGroups =UserGroup::onlyTrashed()->find($id);
        if ($userGroups->restore()) {
          return redirect()->back()->with('success',"Restore successfully");
        }else {
          return redirect()->back()->with('error',"There is something wrong please try again!");
        }
      }
      public function delete(UserGroup $userGroups, $id)
      {
        $userGroups = UserGroup::where('id', $id)->forceDelete();
        return redirect()->back()->with('success',"permanently Delete successfully");
      }
}
