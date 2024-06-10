<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Notifications\RescheduleNotification;
use Exception;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //This can be used to dispplay all appointments
    public function show(Request $request)
    {
        try {
            $filter = $request->input('filter'); // get the status filter from the request
            $search = $request->input('search');
            if ($filter || $search) {
                $appointments = Appointment::when($filter, function ($query) use ($filter) {
                    $query->where('status', $filter)
                        ->orWhere('date', $filter);
                })
                    ->when($search, function ($query) use ($search) {
                        $query->where(function ($subQuery) use ($search) {
                            $subQuery->where('date', 'LIKE', "%{$search}%")
                                ->orWhere('appointment_type', 'LIKE', "%{$search}%")
                                ->orWhere('status', 'LIKE', "%{$search}%");
                        });
                    })->paginate(10); // paginate the results with 10 results per page
            } else {
                $appointments = Appointment::paginate(10);
            }

            return response()->json([
                'Appoinments' => $appointments,
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
        try {
            $appointment = Appointment::findOrFail($id);
            return response()->json([
                'Appoinments' => $appointment,
                'message' => 'All appointment is successfully fetched',
                'status' => 'ok'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'error' => $error,
            ]);
        }
    }
    public function edit(Request $request, $id)
    {   
        
        $request->validate([
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
            'status' => 'required|string',
            'appointment_type' => 'required|string',
            'description' => 'nullable'
        ]);
        //This can be used to find the appointment by id 
        $appointment = Appointment::findOrFail($id);
        $originalTime = $appointment->time;
        $originalDate = $appointment->date;
        $user = $appointment->patient;

          //This can be used to udate the appointment
        $appointment->update($request->only('time', 'date', 'status', 'appointment_type', 'description'));
          //This can be used to send message on whatsapp for rescheduled
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
            
        }
        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'Appointment edited successfully and notification sent successfully',
            'status' => 'ok'
        ]);

    }
    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'All appointment is deleted successfully',
            'status' => 'ok'
        ]);
    }
    public function todayAppointment()
    {

        $todayAppointment = Appointment::where('date', Carbon::today())->get();
        return response()->json([
            'Appoinments' => $todayAppointment,
            'message' => 'today appointment fetched successfully',
            'status' => 'ok'
        ]);
    }
    public function editTodayAppointment(Request $request, $id)
    {
        $request->validate([
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
            'status' => 'required|string',
            'appointment_type' => 'required|string',
            'description' => 'nullable'
        ]);
        $appointment = Appointment::whereDate('date', Carbon::today())->find($id);
        if (!$appointment) {
            return response()->json([
                'error' => 'This appointment is  not found for today\'s appointment',
            ], 404);
        }
        $appointment->update($request->only('time', 'date', 'status', 'appointment_type', 'description'));

        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'All appointment is edited successfully',
            'status' => 'ok'
        ]);
    }
    public function cancel(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update((['status' => 'cancelled']));
        return response()->json([
            'Appoinments' => $appointment,
            'message' => 'All appointment is edited successfully',
            'status' => 'ok'
        ]);
    }



}