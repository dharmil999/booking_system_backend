<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    public const IS_CLOSED_YES = 1;
    public const IS_CLOSED_NO = 2;

    protected $fillable = [
        'weekday',
        'is_closed',
        'open_time',
        'close_time',
    ];
}
