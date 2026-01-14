<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("includes/db.php");

header("Content-Type: application/json");

/* AUTH */
if (!isset($_SESSION['admin_email'])) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Unauthorized access"
    ));
    exit;
}

/* INPUT */
$event_id  = isset($_POST['event']) ? (int)$_POST['event'] : 0;
$v_details = isset($_POST['v_details']) ? trim($_POST['v_details']) : '';
$v_link    = isset($_POST['v_link']) ? trim($_POST['v_link']) : '';

if ($event_id <= 0 || $v_link == '') {
    echo json_encode(array(
        "status" => "error",
        "message" => "Invalid input data"
    ));
    exit;
}

/* INSERT */
$stmt = $con->prepare(
    "INSERT INTO event_gallery_video (event_id, title, youtube_url)
     VALUES (?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(array(
        "status" => "error",
        "message" => $con->error
    ));
    exit;
}

$stmt->bind_param("iss", $event_id, $v_details, $v_link);

if ($stmt->execute()) {
    echo json_encode(array(
        "status" => "success",
        "message" => "Video added successfully"
    ));
} else {
    echo json_encode(array(
        "status" => "error",
        "message" => $stmt->error
    ));
}

$stmt->close();
