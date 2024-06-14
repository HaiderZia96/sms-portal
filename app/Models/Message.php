<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'description', 'group_id', 'created_by', 'updated_by'
    ];

    protected $dates = [
        'deleted_at'
    ];
    public function Usergroups()
    {
        return $this->belongsTo('App\Models\UserGroup', 'group_id');
    }
}
