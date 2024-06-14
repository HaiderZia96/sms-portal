<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mask', 'created_by', 'updated_by'
    ];

    protected $dates = [
        'deleted_at'
    ];
    
    public function users()
    {
        return $this->hasOne('App\User', 'group_id');
    }
}
