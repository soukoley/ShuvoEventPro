<?php
session_start();
include("includes/db.php");

header("Content-Type: application/json");

/* ================= AUTH ================= */
if (!isset($_SESSION['admin_email'])) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Unauthorized access"
    ));
    exit;
}

/* ================= INPUT ================= */
$event     = isset($_POST['event']) ? trim($_POST['event']) : '';
$event_det = isset($_POST['event_det']) ? trim($_POST['event_det']) : '';
$price     = isset($_POST['price']) ? (float)$_POST['price'] : 0;

/* ================= VALIDATION ================= */
if ($event == '' || $event_det == '' || $price < 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Invalid input data"
    ));
    exit;
}

/* ================= IMAGE ================= */
if (!isset($_FILES['e_image']) || $_FILES['e_image']['error'] != 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Image upload failed"
    ));
    exit;
}

$e_image = time() . '_' . $_FILES['e_image']['name'];
$tmp_img = $_FILES['e_image']['tmp_name'];

move_uploaded_file($tmp_img, "event_category/" . $e_image);

/* ================= DUPLICATE CHECK ================= */
$chk = $con->prepare("SELECT id FROM event WHERE e_name=?");
$chk->bind_param("s", $event);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Event already exists"
    ));
    $chk->close();
    exit;
}
$chk->close();

/* ================= INSERT ================= */
$stmt = $con->prepare(
    "INSERT INTO event (e_name, e_desc, e_start_price, e_cat_img)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssds", $event, $event_det, $price, $e_image);

if ($stmt->execute()) {
    echo json_encode(array(
        "status" => "success",
        "message" => "Event added successfully"
    ));
} else {
    echo json_encode(array(
        "status" => "error",
        "message" => $stmt->error
    ));
}
$stmt->close();
