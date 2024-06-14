<?php

namespace App\Http\Controllers\Message;


use App\Models\Message;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MessageController extends Controller
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
        $data = Message::withTrashed()->get();
        return view('admin.message.create', compact('data'));
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
            'text' => 'required|unique:messages|string',
        ]);
        $i['created_by'] = Auth::user()->name;
        $i['group_id'] = Auth::user()['group_id'];
          if(Message::create($i)){
            return redirect('message/create')->with('success', 'Message List Successfully Added');
          }else{
            return redirect('message/create')->with('error','There is something wrong please try again!');
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
    public function getMessage(Request $request)
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
        $totalRecords = Message::select('count(*) as allcount')->count();
        }else{
            $totalRecords =  Message::select('count(*) as allcount')->where('group_id', Auth::user()['group_id'])->count();
        }
        if(Auth::user()['role'] == 1){
            $totalRecordswithFilter = Message::select('count(*) as allcount')
                                    ->where('text', 'like', '%' .$searchValue . '%')
                                    ->orWhere('description', 'like', '%' .$searchValue . '%')
                                    ->orWhere('created_by', 'like', '%' .$searchValue . '%')
                                    ->count();
        }else{
            $totalRecordswithFilter = Message::select('count(*) as allcount')
                                    ->where('group_id', Auth::user()['group_id'])
                                    ->where(function ($query) use ($searchValue) {
                                    $query
                                    ->where('text', 'like', '%' .$searchValue . '%')
                                    ->orWhere('description', 'like', '%' .$searchValue . '%')
                                    ->orWhere('created_by', 'like', '%' .$searchValue . '%');
                                    })
                                    ->count();
        }

        
        
        // Fetch records
        if(Auth::user()['role'] == 1){
            $records = Message::orderBy($columnName,$columnSortOrder)
            ->where('text', 'like', '%' .$searchValue . '%')
            ->orWhere('description', 'like', '%' .$searchValue . '%')
            ->orWhere('created_by', 'like', '%' .$searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        }else{
            $records = Message::orderBy($columnName,$columnSortOrder)
                ->where('group_id', Auth::user()['group_id'])
                ->where(function ($query) use ($searchValue) {
                $query
                ->where('text', 'like', '%' .$searchValue . '%')
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
            $text = $record->text;
            $description = $record->description;
            $created_by = $record->created_by;
            $created_at = $record->created_at->format('d-M-Y  H:i:s');
            $data_arr[] = array(
                "id" => $id,
                "text" => $text,
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
    public function UpdateMessage(Request $request)
    {

        
        // Check input
        $messages = array(
                'text.required' => 'The text required.',
                'text.max' => 'The text must be less than :max characters.',
                'description.required' => 'The description required.',
                'description.max' => 'The description must be less than :max characters.',
            );
            
        $validator = Validator::make($request->all(), [
                'text' => 'required|max:100',
                'description' => 'required',
        ], $messages);
        
        if ($validator->fails())
        {
        return redirect()->back()->withErrors($validator->errors());
        }

        $message = Message::find($request->input('message-id'));
        $message->text = trim($request->input('text'));
        $message->description = trim($request->input('description'));
        $message->updated_by = trim(Auth::user()->name);
        $message->save();
        

        $messageId = Message::find($message->id);

        return redirect()->back()->with(['success' => 'Message has been updated successfully.']);

    }
    
    public function deleteMessage($id)
    {
        $message = Message::find($id);
        if($message){
        $destroy = $message->forceDelete($id);

        return redirect()->back()->with(['success' => 'Message has been removed successfully.']);
        }
        else{
            return redirect()->back()->withErrors(['Message associated with submitted id is missing.']);
        }
    }
}
