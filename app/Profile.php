<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public function times()
    {
        return $this->hasMany('App\Alarm');
    }
}
