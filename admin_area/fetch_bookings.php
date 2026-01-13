<?php
include("includes/db.php");
header('Content-Type: application/json');

$result = mysqli_query($con, "SELECT * FROM booking_details");

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    // Normalize DB status
    $statusRaw = strtolower(trim($row['status']));
    $statusClass = 'status-approved'; // default = green

   $statusRaw = strtolower(trim($row['status']));
	$statusClass = 'status-approved'; // default

	if ($statusRaw === 'pending') {
		$statusClass = 'status-pending';
	}
	elseif ($statusRaw === 'completed') {
		$statusClass = 'status-completed';
	}
	elseif ($statusRaw === 'cancelled') {
		$statusClass = 'status-cancelled';
	}
	elseif ($statusRaw === 'rejected') {
		$statusClass = 'status-rejected';
	}
	elseif ($statusRaw === 'approved') {
		$statusClass = 'status-approved';
	}


    $events[] = [
        "id" => $row['id'],
        "title" => $row['e_name'] . " (" . $row['status'] . ")",
        "start" => $row['start_date'],
        "end" => $row['end_date'],
		"max_guest" => $row['max_guest'],
        "classNames" => [$statusClass],
        "extendedProps" => [
            "status" => $row['status']
        ]
    ];
}

echo json_encode($events);
