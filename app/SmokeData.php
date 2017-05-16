<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmokeData extends Model
{
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
