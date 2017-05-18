<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function register(Request $request) {
        $rules = [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'birth_date' => 'required|date',
            'notification_token' => 'required|min:150|max:155',
            'price_per_pack' => 'required|between:0,99.99',
            'cigarettes_per_day' => 'required|integer|digits_between:0,50',
            'cigarettes_per_pack' => 'required|integer|digits_between:0,50'
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
        $profile->price_per_pack = $request->price_per_pack;
        $profile->cigarettes_per_day = $request->cigarettes_per_day;
        $profile->cigarettes_per_pack = $request->cigarettes_per_pack;

        $profile->save();

        return response()->json(['success' => true, 'message' => 'Successfully registered your device'], 200);
    }
}