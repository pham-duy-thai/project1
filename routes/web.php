<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoomController;

Route::resource('rooms', RoomController::class);
Route::resource('students', StudentController::class);


Route::get('/', function () {
    return view('theme');
});

Route::get('/room', function () {
    return view('room.index');
});

Route::get('/room/create', function () {
    return view('room.create');
});

Route::get('/room/edit', function () {
    return view('room.edit');
});
