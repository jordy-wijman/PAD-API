<?php

namespace App\Http\Controllers\Api;

use App\Custom\TileData;
use App\SmokeData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SmokeDataController extends ApiController
{
    public function getTileData(Request $request)
    {
        $this->validateRules($request);

        $tileData = new TileData();

        $amount = SmokeData::whereProfileId($this->profile->id)
            ->whereDay('time_smoked', '=', date('d'))
            ->sum('amount');

        $tileData->smokedToday = $amount;
        $tileData->cigarettesSaved = $this->profile->cigarettes_per_day - $amount;
        $tileData->setSavedMoney(
            $tileData->cigarettesSaved * ($this->profile->price_per_pack / $this->profile->cigarettes_per_pack)
        );
        $tileData->notSmokedFor = "No data found!";

        $lastSmokeData = SmokeData::whereProfileId($this->profile->id)
            ->where('amount', '!=', 0)
            ->orderBy('time_smoked', 'desc')->first();

        if ($lastSmokeData) {
            $lastSmokeDate = Carbon::parse($lastSmokeData->time_smoked);

            $hours = $lastSmokeDate->diffInHours(Carbon::now());
            $minutes = $lastSmokeDate->diffInMinutes(Carbon::now()) - ($hours * 60);
            $date = $hours . ':' . $minutes;
            $tileData->notSmokedFor = $date;
        }

        return response()->json(['success' => true, 'message' => $tileData]);
    }

    public function add(Request $request)
    {
        $this->validateRules($request, ['amount' => 'required|integer|digits_between:0,50']);
        $smokeData = new SmokeData;
        $smokeData->time_smoked = Carbon::now();
        $smokeData->amount = $request->amount;
        $smokeData->profile_id = $this->profile->id;
        $smokeData->added_to_price = false;
        $smokeData->save();

        return response()->json(['success' => true, 'response' => ['smoke_data' => $smokeData]], 200);
    }
}
