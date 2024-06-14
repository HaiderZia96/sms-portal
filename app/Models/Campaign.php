<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscriber_list_id', 'message_id', 'mask', 'group_id', 'title', 'start_date_time', 'description', 'created_by', 'updated_by'   
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function subscriberLists() 
    {
        return $this->belongsTo('App\Models\SubscriberList', 'subscriber_list_id');
    }
    public function messages() 
    {
        return $this->belongsTo('App\Models\Message', 'message_id');
    }
    public function Usergroups()
    {
        return $this->belongsTo('App\Models\UserGroup', 'group_id');
    }
}
