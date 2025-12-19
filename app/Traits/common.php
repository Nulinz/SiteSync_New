<?php

namespace App\Traits;

use App\Models\Employee;
use App\Models\Notify;
use App\Models\New\RolePermission;
use App\Models\New\Permission;
use App\Models\New\Role;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService as fb;

// use Illuminate\Support\Facades\Notification;
// use App\Notifications\GeneralNotification;

trait common
{
    // public function notify_create($to_id, $f_id, $type, $title, $body, $token = [])
    public function notify_create(array $data)
    {
        $to_id = $data['to_id'] ?? null;
        $f_id = $data['f_id'] ?? null;
        $type = $data['type'] ?? null;
        $title = $data['title'] ?? '';
        $body = $data['body'] ?? '';
        $token = $data['token'] ?? [];
        $reminder = $data['reminder'] ?? false;



        // $user = auth()->user();
        $user = Employee::find($to_id);

        log::info($user);

        if (!$user->can('add-notification')) {
            // Log::info("Notification skipped: User {$to_id} lacks 'doc_view' permission.");
            return;
        }

        // Log::Info('Notification not sent: No tokens provided.', $data);

        if (!empty($token)) { // Check if the token array is not empty
            foreach ($token as $tok) {
                try {
                    Notify::create(['to_id' => $to_id, 'f_id' => $f_id, 'type' => $type, 'title' => $title, 'body' => $body, 'c_by' => auth()->user()->id ?? 1, 'reminder' => $reminder]);

                    $response = app(fb::class)->send_notify($tok, $title, $body);
                } catch (\Exception $e) {
                    Log::error('Notification Error: ' . $e->getMessage());
                }
            }
        } else {
            Log::warning('Notification not sent: No tokens provided.');
        }
    }
}
