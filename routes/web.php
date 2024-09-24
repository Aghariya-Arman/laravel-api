<?php

use App\Http\Middleware\ValidUser;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
Route::view('addpost', 'addpost')->middleware('auth', 'verified');
Route::view('allpost', 'allpost')->middleware('auth', 'verified');
