<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("includes/db.php");

header("Content-Type: application/json");

/* AUTH CHECK */
if (!isset($_SESSION['admin_email'])) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Unauthorized access"
    ));
    exit;
}

/* INPUT */
$event_id  = isset($_POST['event']) ? (int)$_POST['event'] : 0;
$event_det = isset($_POST['event_det']) ? trim($_POST['event_det']) : '';

if ($event_id <= 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Please select an event"
    ));
    exit;
}

/* IMAGE CHECK */
if (!isset($_FILES['e_image']) || $_FILES['e_image']['error'] != 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Image upload failed"
    ));
    exit;
}

/* CREATE FOLDER IF NOT EXISTS */
if (!is_dir("event_gallery")) {
    mkdir("event_gallery", 0777, true);
}

$e_image = time() . "_" . $_FILES['e_image']['name'];
$tmp_img = $_FILES['e_image']['tmp_name'];

if (!move_uploaded_file($tmp_img, "event_gallery/" . $e_image)) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Image upload failed (permission issue)"
    ));
    exit;
}

/* INSERT */
$stmt = $con->prepare(
    "INSERT INTO event_gallery (event_id, e_desc, e_img)
     VALUES (?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(array(
        "status" => "error",
        "message" => $con->error
    ));
    exit;
}

$stmt->bind_param("iss", $event_id, $event_det, $e_image);

if ($stmt->execute()) {
    echo json_encode(array(
        "status" => "success",
        "message" => "Image added successfully"
    ));
} else {
    echo json_encode(array(
        "status" => "error",
        "message" => $stmt->error
    ));
}

$stmt->close();