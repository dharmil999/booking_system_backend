<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkingHourCollection extends ResourceCollection
{
    public $workingHours;
    public $slotDuration;

    public function __construct($workingHours, $slotDuration)
    {
        $this->workingHours = $workingHours;
        $this->slotDuration = $slotDuration;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'working_hours' => $this->workingHours->each(function ($workingHour) {
                return new WorkingHourResource($workingHour);
            }),
            'slot_duration' => $this->slotDuration,
        ];
    }
}
