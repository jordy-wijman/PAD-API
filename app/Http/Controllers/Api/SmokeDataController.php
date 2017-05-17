<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\SmokeData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SmokeDataController extends Controller
{
    public function add(Request $request) {
        $rules = [
            'amount' => 'required|integer|digits_between:0,50',
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

        $smokeData = new SmokeData;
        $smokeData->time_smoked = Carbon::now();
        $smokeData->amount = $request->amount;
        $smokeData->profile_id = $profile->id;
        $smokeData->save();
    }
}