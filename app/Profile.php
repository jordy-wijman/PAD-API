<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public function times()
    {
        return $this->hasMany('App\Alarm');
    }

    public function smokeData()
    {
        return $this->hasMany('App\SmokeData');
    }

    public function savingGoals()
    {
        return $this->hasMany('App\SavingGoal');
    }
}
