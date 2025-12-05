<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'service_id',
        'booking_date',
        'start_time',
        'end_time',
        'customer_email',
    ];
}
