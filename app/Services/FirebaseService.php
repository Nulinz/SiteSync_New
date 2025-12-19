<?php

namespace App\Services;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Message;
use Google\Service\FirebaseCloudMessaging\Notification;
use Google\Service\FirebaseCloudMessaging\AndroidConfig;
use Google\Service\FirebaseCloudMessaging\ApnsConfig;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;

class FirebaseService
{
    protected $client;
    protected $messaging;

    public function __construct()
    {
        // Set up the Google client with the service account credentials
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/firebase.json'));
        $this->client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        // Initialize the Firebase Cloud Messaging service
        $this->messaging = new FirebaseCloudMessaging($this->client);
    }

    public function send_notify($token, $title, $body)
    {
        // Create the notification payload
        $notification = new Notification([
            'title' => $title,
            'body' => $body,
        ]);

        // Configure Android push notification
        $androidConfig = new AndroidConfig([
            'priority' => 'high',
        ]);

        // Configure APNs (Apple Push Notification Service) payload
        $apnsConfig = new ApnsConfig([
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'sound' => 'default',
                    'content-available' => 1,
                ],
            ],
        ]);

        // Create the message object
        $message = new Message([
            'token' => $token,
            'notification' => $notification,
            'android' => $androidConfig,
            'apns' => $apnsConfig,
        ]);

        // Prepare the SendMessageRequest
        $sendRequest = new SendMessageRequest([
            'message' => $message,
        ]);

        try {
            // Send the message via Firebase Cloud Messaging
            $response = $this->messaging->projects_messages->send(
                "projects/" . config('firebase.project_id'),
                $sendRequest
            );
            //  return $response;
            //  $responseBody = json_decode($response,true);
              return response()->json(['status'=>'success']);
        } catch (\Exception $e) {
            // Catch and return any errors that occur
            return ['error' => $e->getMessage()];
        }
    }
}
