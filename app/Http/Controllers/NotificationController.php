<?php

namespace App\Http\Controllers;

use App\Custom\Notification;
use App\Profile;
use App\Alarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class NotificationController extends Controller
{
    public function registerProfile(Request $request) {
        $rules = [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'birth_date' => 'required|date',
            'notification_token' => 'required|min:150|max:155',
        ];

        $validation = Validator::make($request->all(), $rules);

        if (!$validation->passes()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        $profile = Profile::where(['notification_token' => $request->notification_token])
            ->orWhere(['first_name' => $request->first_name, 'last_name' => $request->last_name])
            ->first();

        $profile =  $profile ?: new Profile;

        $profile->first_name = $request->first_name;
        $profile->last_name = $request->last_name;
        $profile->birth_date = $request->birth_date;
        $profile->notification_token = $request->notification_token;

        $profile->save();

        return response()->json(['success' => true, 'message' => 'Successfully registered your device'], 200);
    }

    public function addTime(Request $request) {
        $rules = [
            'notification_token' => 'required|min:150|max:155',
            'notification_time' => 'required|min:3|max:5',
        ];

        $validation = Validator::make($request->all(), $rules);

        if (!$validation->passes()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        $profile = Profile::where(['notification_token' => $request->notification_token])->first();

        $timeCheck = Alarm::where(['profile_id' => $profile->id, 'time' => $request->notification_time])->first();
        if ($timeCheck != null) {
            return response()->json(['success' => false, 'message' => 'This time was already added!'], 422);
        }

        $time = new Alarm;
        $time->profile_id = $profile->id;
        $time->time = $request->notification_time;
        $time->save();

        return response()->json(['success' => true, 'message' => 'Successfully added your time!'], 200);
    }

    public function removeTime(Request $request) {
        $rules = [
            'notification_token' => 'required|min:150|max:155',
            'notification_time' => 'required|min:3|max:5',
        ];

        $validation = Validator::make($request->all(), $rules);

        if (!$validation->passes()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ], 422);
        }

        $profile = Profile::where(['notification_token' => $request->notification_token])->first();

        $timeCheck = Alarm::where(['profile_id' => $profile->id, 'time' => $request->notification_time])->first();
        if ($timeCheck == null) {
            return response()->json(['success' => false, 'message' => 'This time was already removed!'], 200);
        }

        $timeCheck->delete();

        return response()->json(['success' => true, 'message' => 'Successfully removed your time!'], 200);
    }

    public function send(Request $request) {
        $this->validate($request, [
            'receiver_first_name' => 'required|max:50',
            'receiver_last_name' => 'required|max:50',
            'notification_title' => 'required|max:50',
            'notification_body' => 'required|max:255'
        ]);
        $profile = Profile::where(
            ['first_name' => $request->receiver_first_name, 'last_name' => $request->receiver_last_name]
        )->first();

        $notification = new Notification($request->notification_title, $request->notification_body);
        $notification->send($profile);

        Session::flash('success', 'Successfully sent your notification!');
        return redirect()->back();
    }

}
