<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use PharIo\Manifest\Email;

class AuthController extends Controller
{
    public function register(Request $request)
    {
         $request->validate([
            'name' => 'required|string|max:32',
            'email' => 'required|email|max:64|regex:/^[a-z].*/',
            'gender' => 'required|string',
            'password' => 'required|min:8',
            'confirmation_password' => 'required',
            'mobile_no' => 'nullable|max:10',
            
         ]);
         try{
         $user = User::create($request->only('name','email','gender','password','mobile_no'));
         
         return response()->json([
            'message' => 'User Registered Successfully',
            'status' => 'success'
        ]);
        }catch(Exception $e){
            return response()->json(['error' => $e]);
        }
         
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        
        $credentials = $request->only('email' , 'password');
        if(Auth::attempt($credentials))
        {
            $user = Auth::user();
            auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'message' => 'User logged in Successfully',
                'status' => 'success'
            ]);
             
        }
        else
        {
            return response()->json([
               
                'message' => 'please enter correct credential',
                'status' => 'error'
            ]);
        }
    }
}