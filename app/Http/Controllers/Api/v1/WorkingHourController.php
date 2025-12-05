<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\WorkingHour\UpdateWorkingHourRequest;
use App\Http\Resources\WorkingHourCollection;
use App\Models\TimeSlotDuration;
use Illuminate\Http\Request;
use App\Models\WorkingHour;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Traits\Common;

class WorkingHourController extends Controller
{
    use Common;

    public function index()
    {
        try {
            $workingHours = WorkingHour::select('id', 'weekday', 'is_closed', 'open_time', 'close_time')->get();
            $slotDuration = TimeSlotDuration::first();
            if (!empty($slotDuration)) {
                $slotDuration = $slotDuration->slot_duration;
            } else {
                $slotDuration = 30;
            }
            return $this->success(new WorkingHourCollection($workingHours, $slotDuration), __('messages.working_hours_fetch_success'));
        } catch (Exception $e) {
            return $this->fail([], $e->getMessage());
        }
    }

    public function update(UpdateWorkingHourRequest $request)
    {
        try {
            $allData = is_array($request->data) ? $request->data : json_decode($request->data, true);
            if (!empty($allData)) {
                DB::beginTransaction();
                $workingHourData = [];
                foreach ($allData as $data) {
                    $workingHourData[] = [
                        'weekday' => $data['weekday'],
                        'is_closed' => $data['is_closed'],
                        'open_time' => $data['open_time'],
                        'close_time' => $data['close_time'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if(!empty($workingHourData)){
                    WorkingHour::query()->delete();
                    WorkingHour::insert($workingHourData);
                }

                if (!empty($request->slot_duration)) {
                    $timeSlotDuration = TimeSlotDuration::first();
                    if (!empty($timeSlotDuration)) {
                        $timeSlotDuration->update(['slot_duration' => $request->slot_duration]);
                    } else {
                        TimeSlotDuration::create(['slot_duration' => $request->slot_duration]);
                    }
                }
                
                DB::commit();
                return $this->success([], __('messages.working_hours_update_success'));
            } else {
                return $this->fail([], __('messages.working_hours_update_failed'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->fail([], $e->getMessage());
        }
    }

    
}
