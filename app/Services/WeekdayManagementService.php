<?php

namespace App\Services;

use App\Models\Weekday;

use App\Http\Resources\WeekdayResource;

class WeekdayManagementService
{
    public static function getWeekdays()
    {
        $weekdays = Weekday::all();
        $weekdayCollection = WeekdayResource::collection($weekdays);
        return $weekdayCollection->response()->getData(true);
    }
}
