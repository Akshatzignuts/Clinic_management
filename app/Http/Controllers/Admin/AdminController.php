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
    //Through this we can see the dashboard data on admin side
    public function index()
    {
        //Through we can the user role count
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
        //Get the data of where the status of the patient is completed 
        $completedPatient = Appointment::where('status' , 'completed')->count();
        //Through this we can get the details of todays appointment  
        $todayAppointment = Appointment::where('date',Carbon::today())->get();
        //through this we can get the count of todays appointments
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