<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DasboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); //Through we can get the  authenticated user
        $AppointmentCount = Appointment::where('doctor_id', $user->id)->whereDate('date', Carbon::today())->count();//Through this we can get the count of todays appointment
        $cancelledAppointmnet = Appointment::where('status', 'cancelled')->count();//Through this we can get the count of cancelled appointment
        $completedAppointmentCount = Appointment::where('status', 'completed')->count();//Through thiswe can get the count of completed appointment
        $appointments = Appointment::where('doctor_id', $user->id)->whereDate('date', Carbon::today())->get(); // through this we can get the details of the todays appointment 

        $totalPatientCount = Appointment::where('doctor_id', $user->id)->count();
        return response()->json([
            'Appointment Count' => $AppointmentCount,
            'Completed Appointment' => $completedAppointmentCount,
            'CancelledAppointment' => $cancelledAppointmnet,
            'Appointments' => $appointments,
            'TotalPatient' => $totalPatientCount
        ]);
    }
    public function changePassword(Request $request, $id)
    {
        //Through this we can validate the fields
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required'
        ]);
        //Through this we can find the authenticated user id 
        $user = Auth::user()->findOrFail($id);
        //through this we can change the password 
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return response()->json([
            'message' => 'password changed successfully',
            'status' => 'success'
        ]);
    }
    


}