<?php

$token = $_POST['token'] ?? $_GET['token'] ?? '';
$title = $_POST['title'] ?? $_GET['title'] ?? 'Test Title';
$body  = $_POST['message'] ?? $_GET['message'] ?? 'Test Message';

if (!$token) {
    die("Token required");
}

// Firebase service account
$serviceAccount = json_decode(file_get_contents(__DIR__ . '/../config/firebase_service.json'), true);

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function getAccessToken($sa) {
    $now = time();

    $header = base64url_encode(json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]));

    $claims = base64url_encode(json_encode([
        'iss'   => $sa['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $now,
        'exp'   => $now + 3600
    ]));

    $unsignedJWT = $header . "." . $claims;

    openssl_sign($unsignedJWT, $signature, $sa['private_key'], 'SHA256');

    $jwt = $unsignedJWT . "." . base64url_encode($signature);

    $ch = curl_init("https://oauth2.googleapis.com/token");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ])
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $response['access_token'] ?? null;
}

// Get access token
$accessToken = getAccessToken($serviceAccount);
if (!$accessToken) die("Access token failed");

// Payload
$payload = [
    "message" => [
        "token" => $token,
        "notification" => [
            "title" => $title,
            "body"  => $body
        ]
    ]
];

// Send request
$url = "https://fcm.googleapis.com/v1/projects/{$serviceAccount['project_id']}/messages:send";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>$response</pre>";
