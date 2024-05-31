<?php

namespace App\Http\Controllers\User;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user)
        {
            $appointments = Appointment::where('date' ,  Carbon::today())->get();
            return response()->json([
                'appointments' => $appointments,    
                'message' => 'Todays Appointments list fetched successfully',
                'status' => 'ok'
            ]);
        }
    }
}