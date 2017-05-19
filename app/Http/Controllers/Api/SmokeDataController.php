<?php

namespace App\Http\Controllers\Api;

use App\Custom\TileData;
use App\Profile;
use App\SmokeData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SmokeDataController extends Controller
{
    public function getTileData(Request $request)
    {
        $rules = [
            'notification_token' => 'required|min:150|max:155'
        ];

        $validation = Validator::make($request->all(), $rules);
        if (!$validation->passes()) {
            return response()->json(
                ['success' => false, 'message' => $validation->errors()],
                422
            );
        }

        $tileData = new TileData();

        $profile = Profile::where(['notification_token' => $request->notification_token])->first();

        $amount = SmokeData::where(['profile_id' => $profile->id])
            ->whereDay('time_smoked', '=', date('d'))
            ->sum('amount');

        $tileData->smokedToday = $amount;
        $tileData->cigarettesSaved = $profile->cigarettes_per_day - $amount;
        $tileData->setSavedMoney($tileData->cigarettesSaved * ($profile->price_per_pack / $profile->cigarettes_per_pack));
        $tileData->notSmokedFor = "No data found!";

        $lastSmokeData = SmokeData::where(
            ['profile_id' => $profile->id]
        )->orderBy('time_smoked', 'desc')->first();

        if ($lastSmokeData) {
            $lastSmokeDate = Carbon::parse($lastSmokeData->time_smoked);

            $date = $lastSmokeDate->diff(Carbon::now())->format("%H:%I");
            $tileData->notSmokedFor = $date;
        }

        return response()->json(['success' => true, 'message' => $tileData]);
    }

    public function add(Request $request)
    {
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

        return response()->json(['success' => true, 'message' => 'Successfully registered your smoke data'], 200);
    }
}
