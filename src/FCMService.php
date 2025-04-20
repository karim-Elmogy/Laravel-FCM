<?php

namespace elmogy\fcm;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class FCMService
{
    protected $credentialsFilePath;
    protected $firebaseProjectId;

    public function __construct()
    {
        $this->setCredentialsFilePath();
        $this->firebaseProjectId = env('FIREBASE_PROJECT_ID', 'default-project-id'); // Fallback to a default value if not set
    }

    /**
     * Set the path to the Firebase credentials file.
     *
     * @return void
     */
    protected function setCredentialsFilePath()
    {
        $isLocalEnvironment = request()->root() === 'http://127.0.0.1:8000' || 'http://localhost:8000';

        $this->credentialsFilePath = $isLocalEnvironment
            ? env('FIREBASE_FILE') // Local path
            : Http::get(asset(env('FIREBASE_FILE'))); // Live server path
    }

    /**
     * Sends a Firebase Notification to a specific device token.
     *
     * @param string $fcmToken The device FCM token.
     * @param string $title The notification title.
     * @param string $body The notification body.
     * @param array $data The notification anther data.
     * @return array The response data.
     */
    public function sendFCM(string $fcmToken, string $title, string $body , array $data = []): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $payload = $this->preparePayload($fcmToken, $title, $body , $data);

            $response = $this->sendRequest($accessToken, $payload);

            return [
                'success' => true,
                'response' => json_decode($response, true),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the access token from Google Client.
     *
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken(): string
    {
        $client = new GoogleClient();
        $client->setAuthConfig($this->credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();

        $token = $client->getAccessToken();

        if (!isset($token['access_token'])) {
            throw new \Exception('Failed to retrieve access token.');
        }

        return $token['access_token'];
    }

    /**
     * Prepare the notification payload.
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    protected function preparePayload(string $fcmToken, string $title, string $body , array $data = []): array
    {
        $message = [
            "token" => $fcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
        ];

        if (!empty($data)) {
            // Check if $data is associative
            if (array_values($data) === $data) {
                throw new \InvalidArgumentException('FCM "data" must be an associative array.');
            }

            $message["data"] = $data;
        }

        return ["message" => $message];
    }

    /**
     * Send the notification request via cURL.
     *
     * @param string $accessToken
     * @param array $payload
     * @return string
     * @throws \Exception
     */
    protected function sendRequest(string $accessToken, array $payload): string
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->firebaseProjectId}/messages:send";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("Curl Error: $error");
        }

        return $response;
    }
}
