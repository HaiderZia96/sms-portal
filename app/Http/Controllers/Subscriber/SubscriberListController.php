<?php

namespace App\Http\Controllers\Subscriber;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriberList;
use Illuminate\Http\Request;
use DB;

class SubscriberListController extends Controller
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
        $data = SubscriberList::withTrashed()->get();
        return view('admin.subscriberList.create', compact('data'));
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
            'title' => 'required|unique:subscriber_lists|string',
        ]);
        $i['created_by'] = Auth::user()->name;
        $i['group_id'] = Auth::user()['group_id'];
          if(SubscriberList::create($i)){
            return redirect('subscriber-list/create')->with('success', 'Subscriber List Successfully Added', ['i']);
          }else{
            return redirect('subscriber-list/create')->with('error','There is something wrong please try again!');
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubscriberList  $subscriberList
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriberList $subscriberList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubscriberList  $subscriberList
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriberList $subscriberList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubscriberList  $subscriberList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriberList $subscriberList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubscriberList  $subscriberList
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriberList $subscriberList)
    {
        //
    }
    public function getSubscriberLists(Request $request)
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
        if(Auth::user()['role'] == 1){
            $totalRecords = SubscriberList::select('count(*) as allcount')->count();
        }else{
            $totalRecords = SubscriberList::select('count(*) as allcount')->where('group_id', Auth::user()['group_id'])->count();
        }
        if(Auth::user()['role'] == 1){
            $totalRecordswithFilter = SubscriberList::select('count(*) as allcount')
                                    ->where('title', 'like', '%' .$searchValue . '%')
                                    ->orWhere('description', 'like', '%' .$searchValue . '%')
                                    ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                                    ->count();
        }else{
                $totalRecordswithFilter = SubscriberList::select('count(*) as allcount')
                                    ->where('group_id', Auth::user()['group_id'])
                                    ->where(function ($query) use ($searchValue) {
                                    $query
                                    ->where('title', 'like', '%' .$searchValue . '%')
                                    ->orWhere('description', 'like', '%' .$searchValue . '%')
                                    ->orWhere('created_by', 'like', '%' .$searchValue . '%');
                                    })
                                    ->count();
            }

        
        
        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = SubscriberList::orderBy($columnName,$columnSortOrder)
                ->where('title', 'like', '%' .$searchValue . '%')
                ->orWhere('description', 'like', '%' .$searchValue . '%')
                ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }else{
            $records = SubscriberList::orderBy($columnName,$columnSortOrder)
                ->where('group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('title', 'like', '%' .$searchValue . '%')
                ->orWhere('description', 'like', '%' .$searchValue . '%')
                ->orWhere('created_by', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }
       

        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as $record){
            $id = $record->id;
            $title = $record->title;
            $description = $record->description;
            $created_by = $record->created_by;
            $created_at = $record->created_at->format('d-M-Y  H:i:s');
            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "description" => $description,
                "created_by" => $created_by,
                "created_at" => $created_at
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
    public function UpdateSubscriber(Request $request)
    {

        
        // Check input
        $messages = array(
                'title.required' => 'The title required.',
                'title.max' => 'The title must be less than :max characters.',
                'description.required' => 'The description required.',
                'description.max' => 'The description must be less than :max characters.',
            );
            
        $validator = Validator::make($request->all(), [
                'title' => 'required|unique:subscriber_lists,title,'.$request->input('subscriber-id'),
                'description' => 'required',
        ], $messages);
        
        if ($validator->fails())
        {
        return redirect()->back()->withErrors($validator->errors());
        }

        $subscriber = SubscriberList::find($request->input('subscriber-id'));
        $subscriber->title = trim($request->input('title'));
        $subscriber->description = trim($request->input('description'));
        $subscriber->updated_by = trim(Auth::user()->name);
        $subscriber->save();
        

        $subscriberId = SubscriberList::find($subscriber->id);

        return redirect()->back()->with(['success' => 'Subscriber List has been updated successfully.']);

    }
    
    public function deleteSubscriber($id)
    {
        $subscriber = SubscriberList::find($id);
        if($subscriber){
        $destroy = $subscriber->forceDelete($id);

        return redirect()->back()->with(['success' => 'Subscriber List has been removed successfully.']);
        }
        else{
            return redirect()->back()->withErrors(['Subscriber List associated with submitted id is missing.']);
        }
    }
}
