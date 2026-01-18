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
$facility   = isset($_POST['facility']) ? trim($_POST['facility']) : '';
$compulsory = isset($_POST['compulsory']) ? intval($_POST['compulsory']) : 0;
$price      = isset($_POST['price']) ? (float)$_POST['price'] : 0;
$splPrice   = isset($_POST['specialPrice']) ? (float)$_POST['specialPrice'] : 0;
$gst_rate   = isset($_POST['gst_rate']) ? (float)$_POST['gst_rate'] : 0;
$event      = isset($_POST['event']) ? trim($_POST['event']) : '';
$max_people = isset($_POST['max_people']) && $_POST['max_people'] !== ''
                ? (int)$_POST['max_people']
                : 0;

/* ================= VALIDATION ================= */
if ($facility == '' || $event == '' || $price < 0 || $gst_rate < 0 || $splPrice < 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Invalid input data"
    ));
    exit;
}

/* Event logic */
if ($event === "ALL") {
    $max_people = 0;
} else {
    if ($max_people < 0) {
        echo json_encode(array(
            "status" => "error",
            "message" => "Please select maximum people for this event"
        ));
        exit;
    }
}

/* ================= DUPLICATE CHECK ================= */
$chk = $con->prepare(
    "SELECT id FROM facility 
     WHERE fName=? AND eName=? AND max_people=?"
);
$chk->bind_param("ssi", $facility, $event, $max_people);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    echo json_encode(array(
        "status" => "error",
        "message" => "Facility already exists"
    ));
    $chk->close();
    exit;
}
$chk->close();

/* ================= INSERT ================= */
$stmt = $con->prepare(
    "INSERT INTO facility (fName, fPrice, splPrice, gst_rate, eName, max_people, compulsory)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "sdddsii",
    $facility,
    $price,
    $splPrice,
    $gst_rate,
    $event,
    $max_people,
    $compulsory
);

if ($stmt->execute()) {
    echo json_encode(array(
        "status" => "success",
        "message" => "Facility added successfully"
    ));
} else {
    echo json_encode(array(
        "status" => "error",
        "message" => $stmt->error
    ));
}

$stmt->close();
