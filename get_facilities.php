<?php
include("includes/db.php");

$eventName = $_GET['eventName'];
$noOfPeople = intval($_GET['noOfPeople']);

if ($eventName === '' || $noOfPeople === '') {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

// Example query — adjust logic to match your table columns
$sql = "SELECT id, fName, fPrice, gst_rate FROM facility 
        WHERE ((max_people = $noOfPeople AND eName = '$eventName')
        OR (eName = 'ALL'))";

$result = $con->query($sql);
$facilities = [];

while($row = $result->fetch_assoc()) {
    $facilities[] = $row;
}

echo json_encode($facilities);
?>
