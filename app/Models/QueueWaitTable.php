<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueueWaitTable extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id', 'message_id', 'mask', 'group_id', 'number', 'msg_id', 'created_by', 'updated_by'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function campaigns() 
    {
        return $this->belongsTo('App\Models\Campaign', 'campaign_id');
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
