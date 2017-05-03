<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
