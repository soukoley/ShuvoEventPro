<?php
include("includes/db.php");
header('Content-Type: application/json');

$sql = "
SELECT 
    b.id,
    b.e_name,
    b.status,
    b.start_date,
    b.end_date,
    b.max_guest,
    b.booking_id,
    b.booking_date,
    b.cust_id,
    c.c_name,
    c.c_mobile
FROM booking_details b
LEFT JOIN customer c 
    ON b.cust_id = c.c_id
";

$result = mysqli_query($con, $sql);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    // Normalize DB status
    $statusRaw = strtolower(trim($row['status']));
    $statusClass = 'status-approved'; // default

    if ($statusRaw === 'pending') {
        $statusClass = 'status-pending';
    } elseif ($statusRaw === 'completed') {
        $statusClass = 'status-completed';
    } elseif ($statusRaw === 'cancelled') {
        $statusClass = 'status-cancelled';
    } elseif ($statusRaw === 'rejected') {
        $statusClass = 'status-rejected';
    } elseif ($statusRaw === 'approved') {
        $statusClass = 'status-approved';
    }

    $events[] = [
        "id"    => $row['id'],
        "title" => $row['e_name'] . " (" . $row['status'] . ")",
        "start"=> $row['start_date'],
        "end"  => $row['end_date'],
        "classNames" => [$statusClass],

        // 👇 These go into SweetAlert via extendedProps
        "extendedProps" => [
            "status"           	=> $row['status'],
            "max_guest"       	=> $row['max_guest'],
            "booking_id"      	=> $row['booking_id'],
            "booking_date"   	=> $row['booking_date'],
            "customer_name"  	=> $row['c_name'],
            "customer_mobile"	=> $row['c_mobile']
        ]
    ];
}

echo json_encode($events);
?>
