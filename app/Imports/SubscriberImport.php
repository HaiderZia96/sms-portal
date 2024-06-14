<?php

namespace App\Imports;

use App\Models\Subscriber;
use Maatwebsite\Excel\Concerns\ToModel;

class SubscriberImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Subscriber([
            'subscriber_list_id'      => session()->get('subscriber_list_id'),
            'name'      => session()->get('name'),
            'email'    => session()->get('email'),
            'description'   => session()->get('description'),
            'created_by'   => session()->get('created_by'),
            'group_id'   => session()->get('group_id'),
            'number'   => $row['0'],
        ]);
    }
}
