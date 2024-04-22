<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/{employee?}', [Controllers\TimetableController::class, 'view'])->name('timetable');
