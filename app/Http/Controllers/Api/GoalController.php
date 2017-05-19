<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\SavingGoal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    public function add(Request $request)
    {
        $rules = [
            'goal' => 'required|max:100',
            'price' => 'required|between:0,99.99',
            'notification_token' => 'required|min:150|max:155',
        ];

        $validation = Validator::make($request->all(), $rules);
        if (!$validation->passes()) {
            return response()->json(
                ['success' => false, 'message' => $validation->errors()],
                422
            );
        }

        $profile = Profile::where(['notification_token' => $request->notification_token])->first();

        $savingGoal = new SavingGoal;
        $savingGoal->goal = $request->goal;
        $savingGoal->price = $request->price;
        $savingGoal->profile_id = $profile->id;
        $savingGoal->save();

        return response()->json(['success' => true, 'message' => 'Successfully added your goal'], 200);
    }
}
