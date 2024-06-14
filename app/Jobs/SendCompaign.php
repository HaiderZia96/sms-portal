<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\QueueWaitTable;

class SendCompaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign_id;
    protected $group_id;
    protected $message_id;
    protected $enNumberRemoveSpace;
    protected $mask;
    protected $m_id;
    protected $created_by;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign_id,$group_id,$message_id,$enNumberRemoveSpace,$mask,$m_id,$created_by)
    {
        $this->campaign_id = $campaign_id;
        $this->group_id = $group_id;
        $this->message_id = $message_id;
        $this->enNumberRemoveSpace = $enNumberRemoveSpace;
        $this->mask = $mask;
        $this->m_id = $m_id;
        $this->created_by = $created_by;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaign_id = (!empty($this->campaign_id)&&isset($this->campaign_id))?$this->campaign_id:'';
        $group_id = (!empty($this->group_id)&&isset($this->group_id))?$this->group_id:'';
        $message_id = (!empty($this->message_id)&&isset($this->message_id))?$this->message_id:'';
        $enNumberRemoveSpace = (!empty($this->enNumberRemoveSpace)&&isset($this->enNumberRemoveSpace))?$this->enNumberRemoveSpace:'';
        $mask = (!empty($this->mask)&&isset($this->mask))?$this->mask:'';
        $m_id = (!empty($this->m_id)&&isset($this->m_id))?$this->m_id:'';
        $created_by = (!empty($this->created_by)&&isset($this->created_by))?$this->created_by:'';
        //Asigning Request Values to variable
        $queueWaitTable = QueueWaitTable::where('msg_id', $m_id)->first();
        //if exist
        if(!empty($queueWaitTable) && isset($queueWaitTable)){
            //update
            $queueWaitTable->campaign_id      = $campaign_id;
            $queueWaitTable->group_id   = $group_id;
            $queueWaitTable->message_id     = $message_id;
            $queueWaitTable->number       = $enNumberRemoveSpace;
            $queueWaitTable->mask   = $mask;
            $queueWaitTable->msg_id   = $m_id;
            $queueWaitTable->created_by = $created_by;
            $checkStatus= Http::get("http://172.15.10.37/api/sms/status/v1/params?pass=v90868vepxjd5bluwqlwsl11g&msg_id=$m_id&submited_time_f=1&zong_status_f=1");
            $checkStatus->json();
            $ckStatus = $checkStatus['message'];
            $status = $ckStatus['msg_status'];
            $queueWaitTable->status     = $status;
            $queueWaitTable->save();
        }
        else
        {
            //insert
            $post = new QueueWaitTable;
            $post->campaign_id      = $campaign_id;
            $post->group_id   = $group_id;
            $post->message_id     = $message_id;
            $post->number       = $enNumberRemoveSpace;
            $post->mask   = $mask;
            $post->msg_id   = $m_id;
            $post->created_by = $created_by;

            $response = Http::get("http://172.15.10.37/api/sms/status/v1/params?pass=v90868vepxjd5bluwqlwsl11g&msg_id=$m_id&submited_time_f=1&zong_status_f=1");
            $response->json();
            $ckStatus = $response['message'];
            $status = $ckStatus['msg_status'];

            $post->status     = $status;

            //Save Message
            $post->save();
        }
    }
}
