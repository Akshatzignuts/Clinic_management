<?php

namespace App\Http\Controllers\User;

use Exception;
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
        if ($user) {
            $appointments = Appointment::where('date', Carbon::today())->get();
            $totalAppointment = Appointment::where('patient_id', $user->id)->count();

            return response()->json([
                'appointments' => $appointments,
                ' Total Appointment' => $totalAppointment,
                'message' => 'Todays Appointments list fetched successfully',
                'status' => 'ok'
            ]);
        }
    }
    public function add(Request $request)
    {
        $user = Auth::user();
        $filter = $request->filter ;
        $search = $request->search ;
        $request->validate([
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
            'appointment_type' => 'required|string',
            'description' => 'nullable',
            'doctor_id' => 'required|exists:users,id',
        ]);
        $appointment = Appointment::create($request->only('time', 'date', 'appointment_type', 'description') +
            ['doctor_id' => $request->doctor_id, 'patient_id' => auth()->user()->id]);
        $appointment->save();
        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'All appointment is added successfully',
            'status' => 'ok'
        ]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
            'appointment_type' => 'required|string',
            'description' => 'nullable'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->only('time', 'date', 'appointment_type', 'description'));

        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'All appointment is edited successfully',
            'status' => 'ok'
        ]);

    }

}