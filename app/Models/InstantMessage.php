<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstantMessage extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'message', 'number', 'msg_id', 'group_id', 'u_id', 'mask', 'status', 'created_by', 'updated_by'
    ];

    protected $dates = [
        'deleted_at'
    ];
    public function Usergroups()
    {
        return $this->belongsTo('App\Models\UserGroup', 'group_id');
    }
}
