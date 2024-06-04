<?php

namespace App\Http\Controllers\Doctor;

use App\Models\User;
use App\Models\Appointment;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function show(Request $request)
    {
        try {
            $filter = $request->input('filter'); // get the status filter from the request
            $search = $request->input('search');
            $user = Auth::user();

            if ($search || $filter) {
                $appointments = Appointment::where('doctor_id', $user->id)
                    ->whereHas('patient', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('mobile_no', 'like', '%' . $search . '%');

                    })
                    ->with('patient')
                    ->get();

                $patients = $appointments->map(function ($appointment) {
                    return [
                        'patient' => $appointment->patient,
                        'appointments' => $appointment
                    ];
                    
                });
            } elseif ($filter) {
                $appointments = Appointment::where('doctor_id', $user->id)
                    ->where('status', $filter)
                    ->with('patient')
                    ->get();

                $patients = $appointments->map(function ($appointment) {
                    return [
                        'patient' => $appointment->patient,
                        'appointments' => $appointment
                    ];
                });


            } else {
                $appointments = Appointment::where('doctor_id', $user->id)
                    ->with('patient')
                    ->get();

                $patients = $appointments->map(function ($appointment) {
                    return [
                        'patient' => $appointment->patient,
                        'appointments' => $appointment
                    ];
                });

                $patientCount = Appointment::where('doctor_id', $user->id)
                    ->distinct('patient_id')
                    ->count('patient_id');

                return response()->json([
                    'Patients' => $patients,
                    'Patient Count' => $patientCount,
                    'message' => 'All appointments are successfully fetched',
                    'status' => 'ok'
                ]);

            }

            return response()->json([
                'Patients' => $patients,
                'message' => 'All appointments are successfully fetched',
                'status' => 'ok'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'error' => $error,
            ]);
        }

    }
    public function view($id)
    {
        $user = auth()->user();
        // Find the specific patient by ID
        $patients = Appointment::where('doctor_id', $user->id)
            ->with('patient')
            ->get()
            ->map(function ($appointment) {
                return $appointment->patient;
            });
        $patient = $patients->firstWhere('id', $id);
        // Check if the patient exists
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }
        // Return the patient details
        return response()->json($patient);
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
        $appointment->update($request->only('time','date','appointment_type','description'));
        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'Appointment is edited successfully',
            'status' => 'ok'
        ]); 
        
    }
}