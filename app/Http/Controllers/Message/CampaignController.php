<?php

namespace App\Http\Controllers\Message;
use App\Models\Campaign;
use App\Models\UserGroup;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use App\Models\SubscriberList;
use App\Models\Message;
use App\Models\Subscriber;
use App\Models\QueueWaitTable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Jobs\QueueTable;
use Carbon\Carbon;

class CampaignController extends Controller
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
        $userGroup = UserGroup::where('id', Auth::user()['group_id'])->first();
        $masks = explode(',', $userGroup->mask);
        $data = Campaign::with('subscriberLists', 'messages')->withTrashed()->get();
        $subscriberLists = SubscriberList::get();
        $messages = Message::get();
        }else{
            $userGroup = UserGroup::where('id', Auth::user()['group_id'])->first();
            $masks = explode(',', $userGroup->mask);
            $data = Campaign::with('subscriberLists', 'messages')->where('group_id', Auth::user()['group_id'])->withTrashed()->get();
            $subscriberLists = SubscriberList::where('group_id', Auth::user()['group_id'])->get();
            $messages = Message::where('group_id', Auth::user()['group_id'])->get();
        }
        return view('admin.campaign.create', compact('data', 'subscriberLists', 'messages', 'masks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = Carbon::now();

        if( $request['start_date_time'] <= $date->format('m/d/Y H:i A') ){
            return redirect()->back()->with('error', 'Schedule date and time should be after the current date and time!');
        }
        $subscriber_list_id = (!empty($request['subscriber_list_id'])&&isset($request['subscriber_list_id']))?$request['subscriber_list_id']:'';
        $message_id = (!empty($request['message_id'])&&isset($request['message_id']))?$request['message_id']:'';
        $title = (!empty($request['title'])&&isset($request['title']))?$request['title']:'';
        $start_date_ti = (!empty($request['start_date_time'])&&isset($request['start_date_time']))?$request['start_date_time']:'';
        $mask = (!empty($request['mask'])&&isset($request['mask']))?$request['mask']:'';
        $description = (!empty($request['description'])&&isset($request['description']))?$request['description']:'';
        $created_by = (!empty(Auth::user()->name)&&isset(Auth::user()->name))?Auth::user()->name:'';
        $group_id = (!empty(Auth::user()['group_id'])&&isset(Auth::user()['group_id']))?Auth::user()['group_id']:'';

        $messages = Message::where('id', $message_id)->first();
        $subscribers = Subscriber::where('subscriber_list_id', $subscriber_list_id)->get();

        //For Showing Message Status
        $subscriber_list_id = (!empty($request['subscriber_list_id'])&&isset($request['subscriber_list_id']))?$request['subscriber_list_id']:'';
        $message_id = (!empty($request['message_id'])&&isset($request['message_id']))?$request['message_id']:'';
        $title = (!empty($request['title'])&&isset($request['title']))?$request['title']:'';
        $start_date_ti = (!empty($request['start_date_time'])&&isset($request['start_date_time']))?$request['start_date_time']:'';
        $mask = (!empty($request['mask'])&&isset($request['mask']))?$request['mask']:'';
        $description = (!empty($request['description'])&&isset($request['description']))?$request['description']:'';
        $created_by = (!empty(Auth::user()->name)&&isset(Auth::user()->name))?Auth::user()->name:'';
        $group_id = (!empty(Auth::user()['group_id'])&&isset(Auth::user()['group_id']))?Auth::user()['group_id']:'';
        $campaign                      = new Campaign;
        $campaign->subscriber_list_id  = $subscriber_list_id;
                $campaign->message_id          = $message_id;
                $campaign->title               = $title;
                $campaign->start_date_time     = date('Y-m-d h:i:s',strtotime($start_date_ti));
                $campaign->mask                = $mask;
                $campaign->description         = $description;
                $campaign->created_by          = $created_by;
                $campaign->group_id            = $group_id;
                $campaign->save();
                $campaign_id = $campaign->id;
                $start_date = $campaign->start_date_time;
        $start_dt = Campaign::where('start_date_time', $start_date)->first();
        $start_date_time = $start_dt->start_date_time;

        $current_date = Carbon::now()->toDateTimeString();
            $emailJob   = (new QueueTable($subscriber_list_id,$message_id,$title,$start_date_time,$mask,$description,$created_by, $group_id,$campaign_id));
            dispatch($emailJob);
        return Redirect('campaign/create')->with('success', 'Compaign Sent and Execute with time please click on this button! ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $showDet = QueueWaitTable::where('campaign_id', $id)->first();
        $showDetails = (!empty( $showDet['campaign_id'])&&isset( $showDet['campaign_id']))? $showDet['campaign_id']:'';
        return view('admin.campaign.show', compact('showDetails'));
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
    public function sendCompaign(Request $request)
    {
        $subscriber_list_id = (!empty($request['subscriber_list_id'])&&isset($request['subscriber_list_id']))?$request['subscriber_list_id']:'';
        $message_id = (!empty($request['message_id'])&&isset($request['message_id']))?$request['message_id']:'';
        $title = (!empty($request['title'])&&isset($request['title']))?$request['title']:'';
        $start_date_time = (!empty($request['start_date_time'])&&isset($request['start_date_time']))?$request['start_date_time']:'';
        $mask = (!empty($request['mask'])&&isset($request['mask']))?$request['mask']:'';
        $description = (!empty($request['description'])&&isset($request['description']))?$request['description']:'';
        $created_by = (!empty(Auth::user()->name)&&isset(Auth::user()->name))?Auth::user()->name:'';
        $group_id = (!empty(Auth::user()['group_id'])&&isset(Auth::user()['group_id']))?Auth::user()['group_id']:'';
        $emailJob   = (new QueueTable($subscriber_list_id,$message_id,$title,$start_date_time,$mask,$description,$created_by, $group_id))->delay(Carbon::now()->addSeconds(1));
        dispatch($emailJob);
        return Redirect('campaign/create')->with('success', 'Compaign Sent and Execute with time');
    }
    public function getCampaign(Request $request)
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
            $totalRecords = Campaign::select('count(*) as allcount')->count();
        }else
        {
            $totalRecords = Campaign::select('count(*) as allcount')->where('group_id', Auth::user()['group_id'])->count();
        }
        if(Auth::user()['role'] == 1){
            $totalRecordswithFilter = Campaign::select('count(*) as allcount')
                                    ->where('mask', 'like', '%' .$searchValue . '%')
                                    ->orWhere('title', 'like', '%' .$searchValue . '%')
                                    ->count();
        }else
        {
            $totalRecordswithFilter = Campaign::select('count(*) as allcount')
                                    ->where('group_id', Auth::user()['group_id'])
                                    ->where(function ($query) use ($searchValue) {
                                    $query
                                    ->where('mask', 'like', '%' .$searchValue . '%')
                                    ->orWhere('title', 'like', '%' .$searchValue . '%');
                                    })
                                    ->count();
        }



        // Fetch records
        if(Auth::user()['role'] == 1){
            $records =  Campaign::orderBy($columnName,$columnSortOrder)
            ->where('mask', 'like', '%' .$searchValue . '%')
            ->orWhere('title', 'like', '%' .$searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }else{
            $records = Campaign::orderBy($columnName,$columnSortOrder)
                ->where('group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('mask', 'like', '%' .$searchValue . '%')
                ->orWhere('title', 'like', '%' .$searchValue . '%');
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
            $mask = $record->mask;
            $title = $record->title;
            $list_id = $record->subscriber_list_id;
            $subscriber = (!empty($record->subscriberLists->title)&&isset($record->subscriberLists->title))?$record->subscriberLists->title:'';
            $messageId = $record->message_id;
            $message = \Illuminate\Support\Str::limit((!empty($record->messages->text)&&isset($record->messages->text))?$record->messages->text:'', 50);
            $start_date_time = $record->start_date_time;
            $description = $record->description;
            $created_by = $record->created_by;
            $created_at = $record->created_at->format('d-M-Y  H:i:s');
            $data_arr[] = array(
                "id" => $id,
                "mask" => $mask,
                "title" => $title,
                "list_id" => $list_id,
                "subscriber_list_id" => $subscriber,
                "messageId" => $messageId,
                "message_id" => $message,
                "start_date_time" => $start_date_time,
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

    public function getCampaignDetail(Request $request, $campaign_id)
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
        $totalRecords = QueueWaitTable::with('campaigns','messages')->select('count(*) as allcount')
        ->where('campaign_id', $campaign_id)->count();
        $totalRecordswithFilter = QueueWaitTable::with('campaigns','messages')->select('count(*) as allcount')
                                    ->where('campaign_id', $campaign_id)
                                    ->where(function ($query) use ($searchValue) {
                                    $query
                                    ->where('number', 'like', '%' .$searchValue . '%')
                                    ->orWhere('mask', 'like', '%' .$searchValue . '%');
                                    })
                                    ->count();



        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = QueueWaitTable::with('campaigns','messages')->orderBy($columnName,$columnSortOrder)
            ->where('campaign_id', $campaign_id)
            ->where(function ($query) use ($searchValue) {
            $query
            ->where('number', 'like', '%' .$searchValue . '%')
            ->orWhere('mask', 'like', '%' .$searchValue . '%');
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }else{
            $records = QueueWaitTable::with('campaigns','messages')->orderBy($columnName,$columnSortOrder)
            ->where('campaign_id', $campaign_id)
            ->where('group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('number', 'like', '%' .$searchValue . '%')
                ->orWhere('mask', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }

        $data_arr = array();
        $sno = $start+1;
        $campus = '';
        foreach($records as  $key => $record){
                $id = $record->id;
                $campaign_title = $record['campaigns']['title'];
                $message_title = $record['messages']['text'];
                $number = $record->number;
                $mask = $record->mask;
                $status = $record->status;
                $data_arr[] = array(
                    "id" => $id,
                    "campaign_title" => $campaign_title,
                    "message_title" => $message_title,
                    "number" => $number,
                    "mask" => $mask,
                    "status" => $status
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
    public function getAllMessage(Request $request)
    {
        $records = Message::all();
        echo json_encode($records);
        exit;
    }
    public function UpdateCampaign(Request $request)
    {


        // Check input
        $messages = array(
                'subscriber_list_id.required' => 'The subscriber_list_id required.',
                'subscriber_list_id.max' => 'The subscriber_list_id must be less than :max characters.',
                'mask.required' => 'The mask required.',
            );

        $validator = Validator::make($request->all(), [
                'subscriber_list_id' => 'required|max:100',
                'mask' => 'required',
        ], $messages);

        if ($validator->fails())
        {
        return redirect()->back()->withErrors($validator->errors());
        }

        $campaign = Campaign::find($request->input('campaign-id'));
        $campaign->subscriber_list_id = trim($request->input('subscriber_list_id'));
        $campaign->message_id          = trim($request->input('message_id'));
        $campaign->title               = trim($request->input('title'));
        $campaign->start_date_time     = trim($request->input('start_date_time'));
        $campaign->mask                = trim($request->input('mask'));
        $campaign->description         = trim($request->input('description'));
        $campaign->updated_by = trim(Auth::user()->name);
        $campaign->save();


        $campaignId = Campaign::find($campaign->id);

        return redirect()->back()->with(['success' => 'Campaign  has been updated successfully.']);

    }

    public function deleteCampaign($id)
    {
        $campaign = Campaign::find($id);
        if($campaign){
        $destroy = $campaign->forceDelete($id);

        return redirect()->back()->with(['success' => 'Campaign  has been removed successfully.']);
        }
        else{
            return redirect()->back()->withErrors(['Campaign  associated with submitted id is missing.']);
        }
    }
}
