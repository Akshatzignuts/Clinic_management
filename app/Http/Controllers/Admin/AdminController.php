<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Appointment;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $user = User::where('role', 'user')->count();
        $appointmentsGroupedByDate = Appointment::select('date')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->date)->format('Y-m-d');
        });
        // Count the number of appointments for each day
        $totalAppointmentsPerDay = $appointmentsGroupedByDate->map(function ($day) {
            return count($day);
        });
        // Calculate the average number of appointments per day
        $averageDailyAppointments = $totalAppointmentsPerDay->avg();
        $completedPatient = Appointment::where('status' , 'completed')->count();
        $todayAppointment = Appointment::where('date',Carbon::today())->get();
        $totalTodayAppointment = Appointment::where('date',Carbon::today())->count();
        
            return response()->json([
                'TotalCout' => $user,
                'Daily Average' => $averageDailyAppointments ,
                'Total Patient Comleted' => $completedPatient,
                'Today Appointment list' => $todayAppointment,
                'Total Todays Appointment' => $totalTodayAppointment,
                'status' => 'ok'
            ]);
            
    }
    
}