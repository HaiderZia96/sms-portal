<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\QueueWaitTable;
use App\Models\Message;
use App\Models\Subscriber;
use App\Models\Campaign;
use App\Jobs\SendCompaign;
use Carbon\Carbon;

class QueueTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //getting value from controller
    protected $subscriber_list_id;
    protected $message_id;
    protected $title;
    protected $start_date_time;
    protected $mask;
    protected $description;
    protected $created_by;
    protected $group_id;
    protected $campaign_id;

    public function __construct($subscriber_list_id,$message_id,$title,$start_date_time,$mask,$description,$created_by, $group_id, $campaign_id)
    {
        $this->subscriber_list_id = $subscriber_list_id;
        $this->message_id = $message_id;
        $this->title = $title;
        $this->start_date_time = $start_date_time;
        $this->mask = $mask;
        $this->description = $description;
        $this->created_by = $created_by;
        $this->group_id = $group_id;
        $this->campaign_id = $campaign_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscriber_list_id = (!empty($this->subscriber_list_id)&&isset($this->subscriber_list_id))?$this->subscriber_list_id:'';
        $message_id = (!empty($this->message_id)&&isset($this->message_id))?$this->message_id:'';
        $title = (!empty($this->title)&&isset($this->title))?$this->title:'';
        $start_date_time = (!empty($this->start_date_time)&&isset($this->start_date_time))?$this->start_date_time:'';
        $mask = (!empty($this->mask)&&isset($this->mask))?$this->mask:'';
        $description = (!empty($this->description)&&isset($this->description))?$this->description:'';
        $created_by = (!empty($this->created_by)&&isset($this->created_by))?$this->created_by:'';
        $group_id = (!empty($this->group_id)&&isset($this->group_id))?$this->group_id:'';
        $campaign_id = (!empty($this->campaign_id)&&isset($this->campaign_id))?$this->campaign_id:'';

        $messages = Message::where('id', $message_id)->first();
        $subscribers = Subscriber::where('subscriber_list_id', $subscriber_list_id)->get();
        $campaign                      = new Campaign;

        $start_dt = Campaign::where('start_date_time', $start_date_time)->first();
        $current_date = Carbon::now()->toDateTimeString();

        $data = array();
        foreach ($subscribers as $subscriber){
//                    $data['subscribers->number'][] = array(

            //Send Message With API
            $enMsg= urlencode($messages->text);
            $enNumber= urlencode($subscriber->number);//for sending number in new line
            $enNumber01= str_replace('%0D%0A',',', $enNumber);//for replacing New Line with comma
            $enNumberRemoveSpace= str_replace('+',',', $enNumber01);//for replacing Space with comma
            $response=  Http::get("http://172.15.10.37/api/sms/queuePortal/v1/params?pass=v90868vepxjd5bluwqlwsl11g&group=portal&mask=$mask&number=$enNumberRemoveSpace&message=$enMsg");
            $data = $response->json();
            sleep(5);
            $message =  $response['message'];
            $m_id =   (isset($message['msg_id'])?$message['msg_id']:'');
            $sendJob   =  (new SendCompaign($campaign_id,$group_id,$message_id,$enNumberRemoveSpace,$mask,$m_id,$created_by));
            dispatch($sendJob); 
//                );
        }
    }
}
