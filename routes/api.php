<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Doctor\DasboardController;
use App\Http\Controllers\Doctor\LoginController;
use App\Http\Controllers\AuthController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/index', [AdminController::class, 'index']);


    Route::get('/appointments', [AppointmentController::class, 'show']);
    Route::prefix('/appointment')->group(function () {
        Route::post('/view/{id}', [AppointmentController::class, 'view']);
        Route::post('/edit/{id}', [AppointmentController::class, 'edit']);
        Route::post('/delete/{id}', [AppointmentController::class, 'delete']);
    });
    Route::post('/appointments-today', [AppointmentController::class, 'todayAppointment']);
    Route::post('appointment-today/edit/{id}', [AppointmentController::class, 'editTodayAppointment']);
    Route::post('appointment/cancel/{id}', [AppointmentController::class, 'cancel']);

    Route::get('/employees', [EmployeeController::class, 'display']);
    Route::post('/add/employee', [EmployeeController::class, 'addEmployee']);
    Route::post('/edit/employee/{id}', [EmployeeController::class, 'editEmployee']);
    Route::post('/delete/employee/{id}', [EmployeeController::class, 'deleteEmployee']);


    Route::post('/your-appointments', [DasboardController::class, 'index']);
    Route::post('/change/password/{id}', [DasboardController::class, 'changePassword']);

    Route::post('your-patients', [PatientController::class, 'show']);
    Route::post('your-patient/{id}', [PatientController::class, 'view']);
});

Route::post('/reset-password', [LoginController::class, 'resetPassword']);