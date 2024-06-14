<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\InstantMessage;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendInstantMessageJob;
use Carbon\Carbon;
use Auth;

class InstantMessageController extends Controller
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
        $userGroup = UserGroup::where('id', Auth::user()['group_id'])->first();
        $masks = explode(',', $userGroup->mask);
        $data = InstantMessage::withTrashed()->get();
        return view('admin.instantMessage.create', compact('data', 'masks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $instantMessage = new InstantMessage();
        $request->validate([
            'message' => 'required',
            'number' => 'required',
            'mask' => 'required',
            
        ]);
        // $data = array();
        $numberArrays = explode(',', $request->number);
            $u_id = random_int(10000000, 99999999);
            $title=$request->title;
            $messages=$request->message;
            $number=$request->number;
            $mask=$request->mask;
            $created_by=Auth::user()->name;
            $group_id=Auth::user()['group_id'];

            $instantMessage->title      = $title;
            $instantMessage->message    = $messages;
            $instantMessage->number     = $number;
            $instantMessage->mask       = $mask;
            $instantMessage->created_by = $created_by;
            $instantMessage->group_id   = $group_id;
            $instantMessage->u_id   = $u_id;
            $instantMessage->save();
        $msg_id = [];
        foreach ($numberArrays as $numberArray)
        {
            $numberArray = array( 
            //Asigning Request Values to variable
            $messages=$request->message,
            $number=$numberArray,
            $mask=$request->mask,
            //Send Message With API
            $currentId=$instantMessage->id,
            $enMsg=urlencode($messages),
            $enNumber=urlencode($numberArray),//for sending number in new line
            $enNumber01=str_replace('%0D%0A',',', $enNumber),//for replacing New Line with comma 
            $enNumberRemoveSpace=str_replace('+',',', $enNumber01),//for replacing Space with comma
            $response= Http::get("http://172.15.10.37/api/sms/queuePortal/v1/params?pass=v90868vepxjd5bluwqlwsl11g&group=portal&mask=$mask&number=$enNumberRemoveSpace&message=$enMsg"),
            $response->json(),
            sleep(2),
            $message = $response['message'],
            $status=$message['msg_status'],
            $msg_id= $message['msg_id'],
            $instantJob   = (new SendInstantMessageJob($title,$messages,$number,$mask,$created_by,$group_id,$msg_id,$u_id))->delay(Carbon::now()->addSeconds(3)),
            dispatch($instantJob),
            );
        }
        return Redirect('instant-message/create')->with('success', 'Message Sent and Data Enter Success');
        
        
            
            
        
        
            // dd($message);
            
        //Saving message Log
        // For Showing Message Status
        // if($status=="Error"){
        //     $enter = array('status'=>'0');
        //     DB::table('instant_messages')->where('id', $currentId)->update($enter);
        //     return Redirect('instant-message/create')->with('error', 'Message Not Sent');
        // }else{
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($u_id)
    {
        $showDetails = InstantMessage::where('u_id', $u_id)->first();
        return view('admin.instantMessage.show', compact('showDetails'));
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
    public function getInstantMessage(Request $request)
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
            $totalRecord = InstantMessage::groupBy('u_id')->get();
            $totalRecords = $totalRecord->count();
        $totalRecordswith = InstantMessage::where('title', 'like', '%' .$searchValue . '%')
                                    ->orWhere('message', 'like', '%' .$searchValue . '%')
                                    ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                                    ->orWhere('mask', 'like', '%' .$searchValue . '%')
                                    ->orWhere('number', 'like', '%' .$searchValue . '%')
                                    ->groupBy('u_id')
                                    ->get();
        $totalRecordswithFilter = $totalRecordswith->count();

        
        
        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = InstantMessage::orderBy($columnName,$columnSortOrder)
            ->where('title', 'like', '%' .$searchValue . '%')
            ->orWhere('message', 'like', '%' .$searchValue . '%')
            ->orWhere('created_by', 'like', '%' .$searchValue . '%')
            ->orWhere('mask', 'like', '%' .$searchValue . '%')
            ->orWhere('number', 'like', '%' .$searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->groupBy('u_id')
            ->get();
        }else{
            $records = InstantMessage::orderBy($columnName,$columnSortOrder)
                ->where('group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('title', 'like', '%' .$searchValue . '%')
                ->orWhere('message', 'like', '%' .$searchValue . '%')
                ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                ->orWhere('mask', 'like', '%' .$searchValue . '%')
                ->orWhere('number', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->groupBy('u_id')
            ->get();
        }
       

        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as $record){
            $id = $record->id;
            $title = $record->title;
            $message = \Illuminate\Support\Str::limit($record->message, 60);
            $mask = $record->mask;
            $number = $record->number;
            $status = $record->status;
            $u_id = $record->u_id;
            $msg_id = $record->msg_id;
            $created_by = $record->created_by;
            $created_at = $record->created_at->format('d-M-Y  H:i:s');
            $data_arr[] = array(
                "id" => $id,
                "title" => $title,
                "message" => $message,
                "mask" => $mask,
                "number" => $number,
                "created_by" => $created_by,
                "created_at" => $created_at,
                "status" => $status,
                "msg_id" => $msg_id,
                "u_id" => $u_id
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
    
    public function getInstantDetail(Request $request, $u_id)
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
        $totalRecords = InstantMessage::select('count(*) as allcount')
        ->where('u_id', $u_id)->count();
        $totalRecordswithFilter = InstantMessage::select('count(*) as allcount')
                                    ->where('instant_messages.u_id', $u_id)
                                    ->where(function ($query) use ($searchValue) {
                                    $query
                                    ->where('title', 'like', '%' .$searchValue . '%')
                                    ->orWhere('message', 'like', '%' .$searchValue . '%')
                                    ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                                    ->orWhere('mask', 'like', '%' .$searchValue . '%')
                                    ->orWhere('number', 'like', '%' .$searchValue . '%');
                                    })
                                    ->count();

        
        
        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = InstantMessage::orderBy($columnName,$columnSortOrder)
            ->where('instant_messages.u_id', $u_id)
            ->where(function ($query) use ($searchValue) {
            $query
            ->where('title', 'like', '%' .$searchValue . '%')
            ->orWhere('message', 'like', '%' .$searchValue . '%')
            ->orWhere('created_by', 'like', '%' .$searchValue . '%')
            ->orWhere('mask', 'like', '%' .$searchValue . '%')
            ->orWhere('number', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }else{
            $records = InstantMessage::orderBy($columnName,$columnSortOrder)
            ->where('instant_messages.u_id', $u_id)
            ->where('instant_messages.group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('title', 'like', '%' .$searchValue . '%')
                ->orWhere('message', 'like', '%' .$searchValue . '%')
                ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                ->orWhere('mask', 'like', '%' .$searchValue . '%')
                ->orWhere('number', 'like', '%' .$searchValue . '%');
            })
            ->select('instant_messages.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }
       

        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as  $key => $record){
            if($key > 0){
                $id = $record->id;
                $title = $record->title;
                $message = \Illuminate\Support\Str::limit($record->message, 60);
                $mask = $record->mask;
                $number = $record->number;
                $status = $record->status;
                $u_id = $record->u_id;
                $msg_id = $record->msg_id;
                $created_by = $record->created_by;
                $created_at = $record->created_at->format('d-M-Y  H:i:s');
                $data_arr[] = array(
                    "id" => $id,
                    "title" => $title,
                    "message" => $message,
                    "mask" => $mask,
                    "number" => $number,
                    "created_by" => $created_by,
                    "created_at" => $created_at,
                    "status" => $status,
                    "msg_id" => $msg_id,
                    "u_id" => $u_id
                );
            }
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
    public function instantCheckStatus($msg_id)
    {
        $response= Http::get("http://172.15.10.37/api/sms/status/v1/params?pass=v90868vepxjd5bluwqlwsl11g&msg_id=$msg_id&submited_time_f=1&zong_status_f=1");
                    $response->json();
                    $ckStatus = $response['message'];
                    $status = $ckStatus['msg_status'];
                    $instantMessageStatus     = $status;
        // On Approve Button Updating The status of Event
        $events=InstantMessage::where('msg_id', $msg_id)->update(['status'=>$instantMessageStatus]);
        if($events){       
            return redirect()->back()->with('message','Congratulations,Status is Update.');  
        }
        else{
            return redirect()->back()->with('message','There is something wrong please try again!');
        }
        ;
    }
}
