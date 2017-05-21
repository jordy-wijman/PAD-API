<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmokeData extends Model
{
    protected $table = 'smoke_data';
    public $timestamps = false;

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
