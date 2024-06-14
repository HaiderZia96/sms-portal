<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriberList extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'created_by', 'updated_by', 'group_id'
    ];

    protected $dates = [
        'deleted_at'
    ];
    
    public function Usergroups()
    {
        return $this->belongsTo('App\Models\UserGroup', 'group_id');
    }
}
