<?php

namespace App\Http\Controllers\Doctor;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //this function can be used to reset the password of the employee
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required'
        ]);

        $token = $request->invitation_token;
        $user = User::where('invitation_token', $token)->first();

        $user->password = Hash::make($request->password);
        $user->status = 'accepted';
        $user->save();
        return response()->json([
            'message' => 'password change successfully',
            'status' => 'success'
        ]);
    }
}