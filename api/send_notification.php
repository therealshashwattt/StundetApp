<?php
include "../config/db.php";
include "../config/firebase.php";

$title = $_POST['title'];
$message = $_POST['message'];

$result = $con->query("SELECT fcm_token FROM student_all WHERE fcm_token IS NOT NULL");
$tokens = [];

while($row = $result->fetch_assoc()){
    $tokens[] = $row['fcm_token'];
}

$payload = [
    "registration_ids" => $tokens,
    "notification" => [
        "title" => $title,
        "body" => $message
    ]
];

$headers = [
    "Authorization: key=" . FIREBASE_SERVER_KEY,
    "Content-Type: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
