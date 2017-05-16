<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavingGoal extends Model
{
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
