<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    notify()->success('Welcome to Laravel Notify ⚡️');
    return view('welcome');
});