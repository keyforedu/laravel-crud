<?php

use App\Http\Controllers\PemeriksaanController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('master');
});

Route::resource('admin/user', \App\Http\Controllers\UserController::class);
Route::resource('admin/pemeriksaan', PemeriksaanController::class);

