<?php

use App\Http\Controllers\Api\v1\AppointmentController;
use App\Http\Controllers\Api\v1\ServiceController;
use App\Http\Controllers\Api\v1\WorkingHourController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    

    Route::prefix('services')->group(function () {
        Route::get('',[ServiceController::class,'index']);
    });

    Route::prefix('working-hours')->group(function () {
        Route::get('',[WorkingHourController::class,'index']);
        Route::post('update',[WorkingHourController::class,'update']);
    });
    
    Route::prefix('appointments')->group(function () {
        Route::get('get-slots',[AppointmentController::class,'getBookingSlots']);
        Route::post('create',[AppointmentController::class,'create']);
    });
});