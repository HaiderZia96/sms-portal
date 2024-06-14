<?php

namespace App\Http\Controllers\Subscriber;

use App\Models\Subscriber;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriberList;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubscriberImport;

class SubscriberController extends Controller
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
        if(Auth::user()['role'] == 1){
            $data = Subscriber::with('subscriberLists')->groupBy('subscriber_list_id')->withTrashed()->get();
            $subscriberLists = SubscriberList::get();
        }else{
            $data = Subscriber::with('subscriberLists')->where('group_id', Auth::user()['group_id'])->groupBy('subscriber_list_id')->withTrashed()->get();
            $subscriberLists = SubscriberList::where('group_id', Auth::user()['group_id'])->get();
        }

        return view('admin.subscriber.create', compact('data', 'subscriberLists'));
    }


    public function getSubscrib(Request $request)
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
                $totalRecord = Subscriber::groupBy('subscriber_list_id')->get();
                $totalRecords = $totalRecord->count();
            }else
            {
                $totalRecord = Subscriber::groupBy('subscriber_list_id')->where('group_id', Auth::user()['group_id'])->get();
                $totalRecords = $totalRecord->count();
            }

            if(Auth::user()['role'] == 1){
                $totalRecordswith = Subscriber::leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
                                            ->where('subscribers.name', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscribers.email', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscribers.created_by', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%')
                                            ->groupBy('subscribers.subscriber_list_id')
                                            ->get();
                $totalRecordswithFilter = $totalRecordswith->count();
            }else
            {
                $totalRecordswith = Subscriber::leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
                                        ->where('subscribers.group_id', Auth::user()['group_id'])
                                        ->where(function ($query) use ($searchValue) {
                                            $query
                                            ->where('subscribers.name', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscribers.email', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscribers.created_by', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%');
                                        })
                                        ->groupBy('subscribers.subscriber_list_id')
                                        ->get();
                $totalRecordswithFilter = $totalRecordswith->count();
            }



        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = Subscriber::orderBy($columnName,$columnSortOrder)
            ->leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
            ->where('subscribers.name', 'like', '%' .$searchValue . '%')
            ->orWhere('subscribers.email', 'like', '%' .$searchValue . '%')
            ->orWhere('subscribers.created_by', 'like', '%' .$searchValue . '%')
            ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%')
            ->select('subscribers.*')
            ->skip($start)
            ->take($rowperpage)
            ->groupBy('subscribers.subscriber_list_id')
            ->get();
        }else{
            $records = Subscriber::orderBy($columnName,$columnSortOrder)
                ->leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
                ->where('subscribers.group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('subscribers.name', 'like', '%' .$searchValue . '%')
                ->orWhere('subscribers.email', 'like', '%' .$searchValue . '%')
                ->orWhere('subscribers.created_by', 'like', '%' .$searchValue . '%')
                ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%');
            })
            ->select('subscribers.*')
            ->skip($start)
            ->take($rowperpage)
            ->groupBy('subscribers.subscriber_list_id')
            ->get();
        }


        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as $record){
            $id = $record->id;
            $title = $record->subscriberLists->title;
            $name = $record->name;
            $email = $record->email;
            $created_at = $record->created_at->format('d-M-Y  H:i:s');
            $created_by = $record->created_by;
            $subscriber_list_id = $record->subscriber_list_id;
            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "name" => $name,
                "email" => $email,
                "created_at" => $created_at,
                "created_by" => $created_by,
                "subscriber_list_id" => $subscriber_list_id
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subscriber_list_id        = $request->subscriber_list_id;
        $number                    = $request->number;
        $description               = $request->description;
        $name                      = $request->name;
        $email                     = $request->email;
        $created_by                = Auth::user()->name;
        $group_id                  = Auth::user()['group_id'];
        $subscriber                = new Subscriber;
        $subscriber->subscriber_list_id  = $subscriber_list_id;
        $subscriber->number              = $number;
        $subscriber->description         = $description;
        $subscriber->name                = $name;
        $subscriber->email               = $email;
        $subscriber->created_by          = $created_by;
        $subscriber->group_id            = $group_id;
        if($subscriber->save()){
            return redirect('subscriber/create')->with('success', 'Subscriber Successfully Added');
          }else{
            return redirect('subscriber/create')->with('error','There is something wrong please try again!');
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscribers, $subscriber_list_id)
    {
        $showContactDetails = Subscriber::where('subscriber_list_id', $subscriber_list_id)->first();
        return view('admin.subscriber.show', compact('showContactDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscriber $subscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        //
    }
    public function csvUpload(Request $request)
    {
        $this->validate($request, [
            'subscriber_list_id' => 'required',
            'description' => 'required',
            'number' => 'required|mimes:csv,txt,xlx,xls,xlsx',
        ]);
        $request->session()->put('subscriber_list_id',$request->input('subscriber_list_id'));
        $request->session()->put('description',$request->input('description'));
        $request->session()->put('name',$request->input('name'));
        $request->session()->put('email',$request->input('email'));
        $request->session()->put('created_by',Auth::user()->name);
        $request->session()->put('group_id',Auth::user()['group_id']);
        Excel::import(new SubscriberImport,request()->file('number'));

        return redirect('subscriber/create')->with('success', 'Subscriber Successfully Added');
    }
    public function getSubscribers(Request $request, $subscriber_list_id)
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
        $totalRecords = Subscriber::select('count(*) as allcount')
        ->where('subscriber_list_id', $subscriber_list_id)->count();
        $totalRecordswithFilter = Subscriber::select('count(*) as allcount')
                                            ->leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
                                            ->where('subscribers.subscriber_list_id', $subscriber_list_id)
                                            ->where(function ($query) use ($searchValue) {
                                            $query
                                            ->where('subscribers.number', 'like', '%' .$searchValue . '%')
                                            ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%');
                                        })->count();



        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = Subscriber::with('subscriberLists')->orderBy($columnName,$columnSortOrder)
            ->leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
                ->where('subscribers.subscriber_list_id', $subscriber_list_id)
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('subscribers.number', 'like', '%' .$searchValue . '%')
                ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%');
            })
            ->select('subscribers.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }else{
            $records = Subscriber::with('subscriberLists')->orderBy($columnName,$columnSortOrder)
            ->leftJoin('subscriber_lists','subscriber_lists.id','=','subscribers.subscriber_list_id')
            ->where('subscribers.subscriber_list_id', $subscriber_list_id)
            ->where('subscribers.group_id', Auth::user()['group_id'])
            ->where(function ($query) use ($searchValue) {
                $query
                ->where('subscribers.number', 'like', '%' .$searchValue . '%')
                ->orWhere('subscriber_lists.title', 'like', '%' .$searchValue . '%');
            })
            ->select('subscribers.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }

        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as $record){
            $id = $record->id;
            $name = $record->name;
            $number = $record->number;
            $list_id = $record->subscriber_list_id;
            $subscriber = $record->subscriberLists->title;
            $created_at = $record->created_at->format('d-M-Y  H:i:s');
            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "number" => $number,
                "list_id" => $list_id,
                "subscriber_list_id" => $subscriber,
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
    public function getAllSubscriber(Request $request)
    {
        $records = SubscriberList::all();
        echo json_encode($records);
        exit;
    }
    public function UpdateSubscriber(Request $request)
    {


        // Check input
        $messages = array(
                'subscriber_list_id.required' => 'The subscriber_list_id required.',
                'subscriber_list_id.max' => 'The subscriber_list_id must be less than :max characters.',
                'number.required' => 'The number required.',
                'number.max' => 'The number must be less than :max characters.',
            );

        $validator = Validator::make($request->all(), [
                'subscriber_list_id' => 'required|max:100',
                'number' => 'required',
        ], $messages);

        if ($validator->fails())
        {
        return redirect()->back()->withErrors($validator->errors());
        }

        $subscriber = Subscriber::find($request->input('subscriber-id'));
        $subscriber->subscriber_list_id = trim($request->input('subscriber_list_id'));
        $subscriber->number = trim($request->input('number'));
        $subscriber->updated_by = trim(Auth::user()->name);
        $subscriber->save();


        $subscriberId = Subscriber::find($subscriber->id);

        return redirect()->back()->with(['success' => 'Subscriber  has been updated successfully.']);

    }

    public function deleteSubscriber($id)
    {
        $subscriber = Subscriber::find($id);
        if($subscriber){
        $destroy = $subscriber->forceDelete($id);

        return redirect()->back()->with(['success' => 'Subscriber  has been removed successfully.']);
        }
        else{
            return redirect()->back()->withErrors(['Subscriber  associated with submitted id is missing.']);
        }
    }
}
