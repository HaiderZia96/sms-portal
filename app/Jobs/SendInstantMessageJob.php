<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\InstantMessage;

class SendInstantMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $title;
    protected $messages;
    protected $number;
    protected $mask;
    protected $created_by;
    protected $group_id;
    protected $msg_id;
    protected $u_id;
    public function __construct($title,$messages,$number,$mask,$created_by, $group_id,$msg_id,$u_id)
    {
        $this->title = $title;
        $this->messages = $messages;
        $this->number = $number;
        $this->mask = $mask;
        $this->created_by = $created_by;
        $this->group_id = $group_id;
        $this->msg_id = $msg_id;
        $this->u_id = $u_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $title = (!empty($this->title)&&isset($this->title))?$this->title:'';
        $messages = (!empty($this->messages)&&isset($this->messages))?$this->messages:'';
        $number = (!empty($this->number)&&isset($this->number))?$this->number:'';
        $mask = (!empty($this->mask)&&isset($this->mask))?$this->mask:'';
        $created_by = (!empty($this->created_by)&&isset($this->created_by))?$this->created_by:'';
        $group_id = (!empty($this->group_id)&&isset($this->group_id))?$this->group_id:'';
        $msg_id = (!empty($this->msg_id)&&isset($this->msg_id))?$this->msg_id:'';
        $u_id = (!empty($this->u_id)&&isset($this->u_id))?$this->u_id:'';
        $data = array();
            // foreach ($msg_id as $m_id) {
            //     $m_id = $m_id;
        $queueWaitTable = InstantMessage::where('msg_id', $msg_id)->first();
            if(!empty($queueWaitTable) && isset($queueWaitTable))
            {
            //update
                $instantMessage->title      = $title;
                $instantMessage->message    = $messages;
                $instantMessage->number     = $number;
                $instantMessage->mask       = $mask;
                $instantMessage->group_id   = $group_id;
                $instantMessage->u_id   = $u_id;
                $instantMessage->created_by = $created_by;
                $checkStatus= Http::get("http://172.15.10.37/api/sms/status/v1/params?pass=v90868vepxjd5bluwqlwsl11g&msg_id=$msg_id&submited_time_f=1&zong_status_f=1");
                $checkStatus->json();
                $ckStatus = $checkStatus['message'];
                $status = $ckStatus['msg_status'];
                $instantMessage->status     = $status;
                $instantMessage->msg_id = $msg_id;
                
                //Save Message
                $instantMessage->save();
            }
            else
            {
                //insert
                $post = new InstantMessage();
                $post->title      = $title;
                $post->message    = $messages;
                $post->number     = $number;
                $post->mask       = $mask;
                $post->group_id   = $group_id;
                $post->u_id   = $u_id;
                $post->created_by = $created_by;
                $checkStatus= Http::get("http://172.15.10.37/api/sms/status/v1/params?pass=v90868vepxjd5bluwqlwsl11g&msg_id=$msg_id&submited_time_f=1&zong_status_f=1");
                $checkStatus->json();
                $ckStatus = $checkStatus['message'];
                $status = $ckStatus['msg_status'];
                $post->status     = $status;
                $post->msg_id = $msg_id;
                
                //Save Message
                $post->save();
            }
            // foreach ($numberArrays as $numberArray)
            // { 
                    
            //         $numberArray = array(
            //         $number=$numberArray,
            //         //Asigning Request Values to variable
            //         // $instantMessage = InstantMessage::firstOrNew(['number' => $number],['created_by'=>$m_id],['title' => $title]),
            //         $instantMessage = new InstantMessage(),

            //         $instantMessage->title      = $title,
            //         $instantMessage->message    = $messages,
            //         $instantMessage->number     = $number,
            //         $instantMessage->mask       = $mask,
            //         $instantMessage->group_id   = $group_id,
            //         $instantMessage->u_id   = $u_id,
            //         $instantMessage->created_by = $created_by,


            //         $enMsg=urlencode($messages),
            //         $enNumber=urlencode($numberArray),//for sending number in new line
            //         $enNumber01=str_replace('%0D%0A',',', $enNumber),//for replacing New Line with comma 
            //         $enNumberRemoveSpace=str_replace('+',',', $enNumber01),//for replacing Space with comma
            //         $response= Http::get("http://172.15.10.37/api/sms/queuePortal/v1/params?pass=v90868vepxjd5bluwqlwsl11g&group=portal&mask=$mask&number=$enNumberRemoveSpace&message=$enMsg"),
            //         $response->json(),
            //         $message = $response['message'],
            //         $status =$message['msg_status'],
            //         $m_id =  $message['msg_id'],

            //         sleep(5),

            //         $checkStatus= Http::get("http://172.15.10.37/api/sms/status/v1/params?pass=v90868vepxjd5bluwqlwsl11g&msg_id=$m_id&submited_time_f=1&zong_status_f=1"),
            //         $checkStatus->json(),
            //         $ckStatus = $checkStatus['message'],
            //         $status = $ckStatus['msg_status'],
            //         $instantMessage->status     = $status,
            //         $instantMessage->msg_id = $m_id,
                    
            //         //Save Message
            //         $instantMessage->save(),
                    
            //         );
            //     }

            
            // }




        // $check = InstantMessage::where('u_id', $u_id)->first();
        //         if(!empty($check) && isset($check)){
        //             $checkUids = InstantMessage::where('u_id', $u_id)->get();
        //             foreach($checkUids as $checkUid){   
        //                 $checkUid->status = 'hy change';
        //                 $checkUid->save();
        //             }
        //         }
        //         else{
            // }
    }
}
