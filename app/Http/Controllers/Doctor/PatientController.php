<?php

namespace App\Http\Controllers\Doctor;

use App\Models\User;
use App\Models\Appointment;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Notifications\RescheduleNotification;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function show(Request $request)
    {
        try {
            $filter = $request->input('filter'); // get the status filter from the request
            $search = $request->input('search');
            $user = Auth::user();
            //this can  be used to filter and search the patient 
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
        //this can be used to find the appointment id for edit 
        $appointment = Appointment::findOrFail($id);
        $originalTime = $appointment->time;
        $originalDate = $appointment->date;
        $user = $appointment->patient;

        //this can be used to update the data of the appointment
        $appointment->update($request->only('time', 'date', 'appointment_type', 'description'));
        
        if (($originalTime !== $request->time) || ($originalDate !== $request->date)) {
            Notification::route('mail', $user->email)->notify(new RescheduleNotification());
            $twilioSid = env('TWILIO_SID');
            $twilioToken = env('TWILIO_AUTH_TOKEN');
            $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
            // $receientNumber = "whatsap:+918780868841";

            $twilio = new Client($twilioSid, $twilioToken);
            $message = $twilio->messages
                ->create(
                    "whatsapp:+918239239550", // to
                    array(
                        "from" => "whatsapp:+14155238886",
                        "body" => "Their is some change in apointment schedule , The new time and date is " . $appointment->time . "and date is "
                            . $appointment->date
                    )
                );
            return response()->json([
                'Appoinments' => $appointment,
                'message' => 'Appointment is edited successfully',
                'status' => 'ok'
            ]);
        }
    }

}