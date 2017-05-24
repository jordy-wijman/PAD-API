<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\SavingGoal;
use App\SmokeData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GoalController extends ApiController
{
    public function add(Request $request)
    {
        $this->validateRules($request, [
            'goal' => 'required|max:100',
            'price' => 'required|between:0,99.99',
        ]);

        $savingGoal = new SavingGoal;
        $savingGoal->goal = $request->goal;
        $savingGoal->price = $request->price;
        $savingGoal->profile_id = $this->profile->id;
        $savingGoal->save();

        return response()->json(['success' => true, 'message' => 'Successfully added your goal'], 200);
    }
}
