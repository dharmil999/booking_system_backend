<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Appointment\CreateAppointmentRequest;
use App\Http\Requests\Api\v1\Appointment\GetBookingSlotRequest;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\TimeSlotDuration;
use App\Models\WorkingHour;
use Exception;
use App\Traits\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    use Common;

    public function create(CreateAppointmentRequest $request)
    {
        try {

            $bookingDate = Carbon::createFromFormat('Y-m-d', $request->booking_date)->format('Y-m-d');
            
            if ((Carbon::createFromFormat('Y-m-d', $request->booking_date)->startOfDay() == Carbon::now()->startOfDay()) && Carbon::parse($request->start_time)->lessThan(Carbon::now())) {
                return $this->fail([], __('messages.invalid_start_time'));
            }

            $timeSlotDuration = TimeSlotDuration::first();
            if (!empty($timeSlotDuration)) {
                $timeSlotDuration = $timeSlotDuration->slot_duration;
            } else {
                $timeSlotDuration = 30;
            }
            $startTime = Carbon::parse($request->start_time);
            $endTime = $startTime->copy()->addMinutes($timeSlotDuration->slot_duration ?? 30);

            $weekDay = Carbon::createFromFormat('Y-m-d', $request->booking_date)->dayOfWeek;

            $workingHour = WorkingHour::where('weekday', $weekDay)->first();
            if (!empty($workingHour)) {
                if ($workingHour->is_closed == WorkingHour::IS_CLOSED_YES) {
                    return $this->fail([], __('messages.working_hour_is_closed'));
                }
                if ($workingHour->open_time > $startTime->format('H:i:s')) {
                    return $this->fail([], __('messages.working_hour_is_not_open'));
                }
                if ($workingHour->close_time < $endTime->format('H:i:s')) {
                    return $this->fail([], __('messages.working_hour_is_not_open'));
                }
            }

            $appointment = Appointment::where('booking_date', $bookingDate)
                ->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime->format('H:i:s'))
                        ->where('end_time', '>', $startTime->format('H:i:s'));
                })
                ->first();

            if (!empty($appointment)) {
                return $this->fail([], __('messages.time_slot_already_booked'));
            }

            $appointment = Appointment::create([
                'service_id' => $request->service_id,
                'booking_date' => $bookingDate,
                'start_time' => $request->start_time,
                'end_time' => $endTime,
                'customer_email' => $request->customer_email,
            ]);

            return $this->success($appointment, __('messages.appointment_create_success'));
        } catch (Exception $e) {
            return $this->fail([], $e->getMessage());
        }
    }

    public function getBookingSlots(GetBookingSlotRequest $request) {
        try {
            $timeSlotDuration = TimeSlotDuration::first();
            if (!empty($timeSlotDuration)) {
                $slotDuration = $timeSlotDuration->slot_duration;
            } else {
                $slotDuration = 30;
            }

            $bookingDate = Carbon::createFromFormat('Y-m-d', $request->booking_date)->format('Y-m-d');

            $weekDay = Carbon::createFromFormat('Y-m-d', $request->booking_date)->dayOfWeek;
            Log::info('Weekday: ' . $weekDay);
            $workingHour = WorkingHour::where('weekday', $weekDay)->first();
            if (empty($workingHour) || $workingHour->is_closed == WorkingHour::IS_CLOSED_YES) {
                return $this->success([], __('messages.working_hour_is_closed'));
            }

            $openTime = Carbon::parse($workingHour->open_time);
            $closeTime = Carbon::parse($workingHour->close_time);

            $appointments = Appointment::where('booking_date', $bookingDate)->get();

            $slots = [];
            while ($openTime->addMinutes(0)->lessThan($closeTime)) {
                $endTime = $openTime->copy()->addMinutes($slotDuration);
                if ($endTime->lessThanOrEqualTo($closeTime)) {
                    // Check if this slot overlaps with any existing appointment
                    $slotStartTime = $openTime->format('H:i:s');
                    $slotEndTime = $endTime->format('H:i:s');
                    
                    $isBooked = $appointments->contains(function ($appointment) use ($slotStartTime, $slotEndTime) {
                        $apptStart = Carbon::parse($appointment->start_time);
                        $apptEnd = Carbon::parse($appointment->end_time);
                        $slotStart = Carbon::parse($slotStartTime);
                        $slotEnd = Carbon::parse($slotEndTime);
                        
                        // Check for overlap: slot starts before appointment ends AND slot ends after appointment starts
                        return $slotStart->lessThan($apptEnd) && $slotEnd->greaterThan($apptStart);
                    });
                    
                    // Only add slot if it's not booked
                    if (!$isBooked) {
                        $slots[] = [
                            'start_time' => $slotStartTime,
                            'end_time' => $slotEndTime,
                        ];
                    }
                }
                $openTime->addMinutes($slotDuration);
            }

            return $this->success($slots, __('messages.services_fetch_success'));
        } catch (Exception $e) {
            return $this->fail([], $e->getMessage());
        }
    }
}
