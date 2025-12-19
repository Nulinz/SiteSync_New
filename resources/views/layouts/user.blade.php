@php
    use App\Models\New\Role;
    $role = Role::where('id', auth()->user()->role_id)->first();
@endphp

<img src="{{ auth()->user()->image_path 
    ? env('AWS_URL') . auth()->user()->image_path 
    : asset('assets/images/avatar.png') }}"
    height="40" width="40" 
    class="border rounded-5" 
    alt="Profile Image">

<h6 class="px-3 py-1 m-0 rounded-1">
    <span class="username">{{ auth()->user()->name }}</span><br>
    <span class="userrole">{{ auth()->user()->role->name ?? 'role' }}</span>
</h6>
