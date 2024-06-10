<?php

namespace App\Http\Controllers\Admin;
use App\Mail\EditEmployee;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\AddEmployee;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function display(Request $request)
    {
        $filter = $request->input('filter'  ); 
        $search = $request->input('search');
        //This can be used to search and filter the user 
        if($filter || $search)
        {
            $employees = User::where('role' , 'doctor')->when($filter, function ($query) use ($filter) {
                $query->where('is_active', $filter);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('is_active', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })->paginate(10); // paginate the results with 10 results per page 
        }
        //This can be used to dislay the all the user 
        else
        {
            $employees = User::where('role' , 'doctor')->paginate(10);
        }
        return response()->json([
           'message' => 'All emloyee data is fetched',
           'empployee' => $employees           
        ]);
    }
    public function addEmployee(Request $request){
        $request->validate([
            'name' => 'required|string|max:32',
            'email' => 'email|required|max:64', 
            'mobile_no' => 'nullable|max:10',
            'role' => 'required|string|max:32',
            'gender' => 'required|string|max:32'
        ]);
       //this can be used to  create random password and invitation token
        $password = Str::random(10);
        $invitation_token = Str::random(30);
        //This can be used to store the data into database 
        $employee = User::create($request->only(['name','email','mobile_no','gender','status','role']) + 
        ['password' => $password, 'status' => 'invited','invitation_token' => $invitation_token]);

        $data = [
            'employee' => $employee,
            'password' => $password
        ];
        //this can be used to send the email of invitation to emoployee
        Mail::to($employee->email)->send(new AddEmployee($data));
        return response()->json([
            'employee' => $employee,
            'status' => 'ok'
        ]);
    }
    public function editEmployee(Request $request , $id){
       $request->validate([
        'name' => 'required|string|max:32',
            'email' => 'email|required|max:64', 
            'mobile_no' => 'nullable|max:10',
            'role' => 'required|string|max:32',
            'gender' => 'required|string|max:32'
       ]);
     
        $user = User::findOrFail($id);
        $user->update($request->only('name','email','mobile_no','role','gender'));
        $data = [
            'employee' => $user,
        ];
        Mail::to($user->email)->send(new EditEmployee($data));
      return response()->json([
        'user' => $user,
        'message' => 'User edited successfully',
        'status' => 'ok'
      ]);
  
    }
    public function deleteEmployee($id){
       
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'user' => $user,
            'message' => 'User deleted successfully',
            'status' => 'ok'
        ]);
       
    }
}