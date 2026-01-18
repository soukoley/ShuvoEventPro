<?php
session_start();
include("includes/db.php");

header("Content-Type: application/json");
date_default_timezone_set('Asia/Kolkata');

/* ================= AUTH ================= */
if (!isset($_SESSION['admin_email'])) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Unauthorized access"
    ));
    exit;
}

/* ================= INPUT ================= */
$hName   = isset($_POST['hname']) ? trim($_POST['hname']) : '';
$hDate   = isset($_POST['hdate']) ? $_POST['hdate'] : '';

/* ================= VALIDATION ================= */
if ($hName == '' || $hDate == '') {
    echo json_encode(array(
        "status" => "error",
        "message" => "Invalid input data"
    ));
    exit;
}

/* ================= DUPLICATE CHECK ================= */
$chk = $con->prepare(
    "SELECT id FROM holidays 
     WHERE h_date=? AND h_name=?"
);
$chk->bind_param("ss", $hDate, $hName);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Holiday already exists"
    ));
    $chk->close();
    exit;
}
$chk->close();

/* ================= INSERT ================= */
$stmt = $con->prepare(
    "INSERT INTO holidays (h_name, h_date)
     VALUES (?, ?)"
);
$date = date("Y-m-d H:i:s");
$stmt->bind_param(
    "ss",
    $hName,
    $hDate
);

if ($stmt->execute()) {
    echo json_encode(array(
        "status" => "success",
        "message" => "Holiday added successfully"
    ));
} else {
    echo json_encode(array(
        "status" => "error",
        "message" => $stmt->error
    ));
}

$stmt->close();
