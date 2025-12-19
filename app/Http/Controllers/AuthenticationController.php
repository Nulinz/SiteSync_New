<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Traits\common;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\New\RolePermission;
use App\Models\New\Permission;
use App\Models\New\Role;



class AuthenticationController extends Controller
{
    use common;
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'employee_code' => 'required',
            'password' => 'required',
        ]);

        $user = Employee::where('employee_code', $credentials['employee_code'])->where('password', $credentials['password'])->where('status', 'active')->first();

        //print_r($user); die;

        if ($user) {
            auth()->login($user);
            return redirect()->route('dashboard.index')->with('success', 'Login successful');
        }

        return back()->withErrors(['Error' => 'Invalid credentials']);
    }

    public function api_login(Request $request)
    {

        $credentials = $request->validate([
            'employee_code' => 'required',
            'password' => 'required',

        ]);

        $user = Employee::where('employee_code', $credentials['employee_code'])->where('status', 'active')->with('m_role')->first();

        // dd($user);



        if ($user) {

            // $auth_token = Str::uuid();

            if ($credentials['password'] == $user->password) {

                $auth_token = $user->createToken('token')->plainTextToken;
                $user->token = $request->device_token ?? null;
                $user->save();

                
                $fileUrl = $user->image_path 
                ? env('AWS_URL') . $user->image_path 
                : null;


                // $profileImageUrl = $user->image_path && file_exists($user->image_path) ? url($user->image_path) : null;

                $roleId = $user->role_id; // Assuming you have role_id in users table
                $role = Role::find($roleId);

                // Get permissions IDs from pivot table
                $permissionIds = RolePermission::where('role_id', $roleId)->pluck('permission_id');

                // Get permission names
                $permissions = Permission::whereIn('id', $permissionIds)->pluck('name');

                $data = [

                    'auth_token' => $auth_token,
                    'id' => $user->id,
                    'employee_code' => $user->employee_code,
                    'name' => $user->name,
                    'role' => $user->m_role->name,
                    'profile' => $fileUrl,
                    'permission' => $permissions
                ];

                return response()->json(['status' => 'success', 'data' => $data]);
            } else {

                return response()->json(['status' => 'User Or Password Mismatch']);
            }
        } else {
            return response()->json(['status' => 'User Not Registered']);
        }
    }

    public function api_logout(Request $request)
    {
        $user = Auth::user(); // Get the currently authenticated user

        if ($user) {
            // Clear the device token if needed
            $user->token = null;
            $user->save(); // Update the existing user in the database

            // Revoke the current access token
            $request->user()->currentAccessToken()->delete();



            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out.',
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function popup(Request $request)
    {
        // $to_id = 1;
        // $f_id = 2;
        // $type = 'task';
        // $title = 'New Task Assigned by Sabari (Assigned by)';
        // $body = 'Flooring (Task Title) in ABC Apartment (Project Name)';
        // $token = [auth()->user()->token];

        // $res = $this->notify_create($to_id, $f_id, $type, $title, $body, $token);

        return response()->json(['version' => '1.0.7'], 200);
    }
}
