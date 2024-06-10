<?php

namespace App\Http\Controllers\User;

use App\Models\Medical_History;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'disease' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'medicine' => 'nullable|array',
        ]);
        //this can be used to add the medical history 
        $medicalHistory = Medical_History::create($request->only('disease', 'date', 'medicine') + ['patient_id' => auth()->user()->id]);

        $medicalHistory->save();
        return response()->json([
            'message' => 'medical history added successfully ',
            'status' => 'ok'
        ]);

    }
    public function view()
    {
        //this can be used to view the medical history
        $medicalHistory = Medical_History::where('patient_id', auth()->user()->id)->get();
        return response()->json([
            'Medical History' => $medicalHistory,
            'message' => 'medical history view successfully ',
            'status' => 'ok'
        ]);
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'disease' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'medicine' => 'nullable|array',
        ]);
        $medicalHistory = Medical_History::findOrFail($id);
        $medicalHistory->update($request->only('disease','date','medicine'));
        return response()->json([
            'Medical History' => $medicalHistory,
            'message' => 'medical history edited successfully ',
            'status' => 'ok'
        ]);
    }
    public function delete($id)
    {
       
        if (!auth()->check()) {
            return response()->json([
                
                'message' => 'You are not logged in',
                'status' => 'ok'
            ]);
        }
    
        // Retrieve the medical history record
        $medicalHistory = Medical_History::find($id);
    
        // Check if the record exists and belongs to the logged in user
        if (!$medicalHistory || $medicalHistory->patient_id!= auth()->id()) {
            return response()->json([
                
                'message' => 'You are not logged in',
                'status' => 'ok'
            ]);
        }
        $medicalHistory->delete();
        return response()->json([
            'message' => 'Medical history deleted successfully',
            'Deleted Medical History' => $medicalHistory
        ]);
    }

}