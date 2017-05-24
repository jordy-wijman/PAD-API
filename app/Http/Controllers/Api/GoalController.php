<?php

namespace App\Http\Controllers\Api;

use App\SavingGoal;
use Illuminate\Http\Request;

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

    public function delete(Request $request)
    {
        $this->validateRules($request, ['id' => 'required|integer']);

        $goal = SavingGoal::where('profile_id',$this->profile->id)
            ->whereNull('fetched_at')
            ->first();

        if (!$goal) {
            return response()->json(
                ['success' => false, 'message' => 'Goal not found or already completed'],
                401
            );
        }

        $goal->delete();
        return response()->json(['success' => true, 'message' => 'Successfully removed your goal'], 200);
    }
}
