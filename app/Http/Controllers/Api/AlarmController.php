<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Profile;
use App\Alarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlarmController extends ApiController
{
    public function add(Request $request)
    {
        $this->validateRules($request, ['notification_time' => 'required|min:3|max:5']);

        $timeCheck = Alarm::where(['profile_id' => $this->profile->id, 'time' => $request->notification_time])->first();
        if ($timeCheck != null) {
            return response()->json(['success' => false, 'message' => 'This time was already added!'], 422);
        }

        $time = new Alarm;
        $time->profile_id = $this->profile->id;
        $time->time = $request->notification_time;
        $time->save();

        return response()->json(['success' => true, 'message' => 'Successfully added your time!'], 200);
    }

    public function remove(Request $request)
    {
        $this->validateRules($request, ['notification_time' => 'required|min:3|max:5']);

        $timeCheck = Alarm::where(['profile_id' => $this->profile->id, 'time' => $request->notification_time])->first();
        if ($timeCheck == null) {
            return response()->json(['success' => false, 'message' => 'This time was already removed!'], 200);
        }

        $timeCheck->delete();

        return response()->json(['success' => true, 'message' => 'Successfully removed your time!'], 200);
    }
}
