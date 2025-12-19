<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class CommonController extends Controller
{

public function downloadFile($file_path)
{
    try {
        // Decode and clean path
        $file_path = urldecode($file_path);
        $file_path = str_replace('storage/', '', $file_path);

        // Check if the file exists on S3
        if (Storage::disk('s3')->exists($file_path)) {
            $file_name = basename($file_path);

            // For private buckets, return file as download
            $fileContent = Storage::disk('s3')->get($file_path);
            $mimeType = Storage::disk('s3')->mimeType($file_path);

            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'attachment; filename="' . $file_name . '"');
        }

        return back()->with('error', 'File not found on S3!');
    } catch (\Exception $e) {
        \Log::error('S3 download error: ' . $e->getMessage());
        return back()->with('error', 'Error downloading file!');
    }
}


    public function assign_role()
    {

        // Role::create([
        //     'name' => 'employee',
        //     // 'name' => 'admin'
        // ]);

        // $per = ['tab-profile', 'tab-survey', 'tab-drawing', 'tab-progress', 'tab-qc', 'tab-snags', 'tab-docs/link'];

        // foreach ($per as $p) {
        //     Permission::create([
        //         'name' => $p,
        //         'guard_name' => 'admin'
        //     ]);
        // }

        // $employee = auth()->user();

        // $employee->givePermissionTo('test-permission');

        // $employee = Employee::find(10);
        // $employee->assignRole('employee');

        // $bol =  $employee->hasPermissionTo('test-permission'); // true/false

        // Example: Admin role and view dashboard permission
        // $role = Role::where('name', 'admin')->first(); // or 'web' if you're using that guard

        // // dd($role);

        // $role->syncPermissions($per);

        // $role_Data =  auth()->user()->getRoleNames()->first();

        // $role = auth()->user()->roles->first(); // This is the full Role model
        // $role_Data = $role->name;

        // // dd($role_Data);

        // $role_id = ($role_Data == 'admin') ? 1 : 2;

        // // $roleId = 1;

        // $role = Role::with('permissions')
        //     ->where('id', $role_id)
        //     ->get()
        //     ->map(function ($item) {
        //         return [
        //             'role' => $item->name,
        //             'permissions' => $item->permissions->pluck('name'),
        //         ];
        //     });

        // return response()->json(['data' => $role]);

        // Create a permission
        // Permission::create(['name' => 'edit posts']);
        // Permission::create(['name' => 'delete posts']);

        // // Create a role and assign permission
        // $editor = Role::create(['name' => 'editor']);
        // $editor->givePermissionTo('edit posts');

        // // Assign role to a user
        // $user = Employee::find(3);
        // $user->assignRole('editor');

        // revoke permission to the role 

        // >>> $role = Spatie\Permission\Models\Role::findByName('editor');
        // >>> $role->revokePermissionTo('edit posts');


        return view('settings.boilerplate');

        // dd($bol);
    }
}
