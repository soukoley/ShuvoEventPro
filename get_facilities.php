<?php
include("includes/db.php");

// =====================
// Sunday / Holiday Checker
// =====================
function hasSundayOrHoliday($start, $end, $con) {

    // Convert to date only
    $startDate = new DateTime(date('Y-m-d', strtotime($start)));
    $endDate   = new DateTime(date('Y-m-d', strtotime($end)));

    // Include end date
    $endDate->modify('+1 day');

    // Get holidays from DB
    $holidays = [];
    $res = mysqli_query($con, "SELECT h_date FROM holidays");
    while ($row = mysqli_fetch_assoc($res)) {
        $holidays[] = $row['h_date'];
    }

    $holidayMap = array_flip($holidays);

    // Loop through date range
    for ($d = clone $startDate; $d < $endDate; $d->modify('+1 day')) {
        $dateStr = $d->format('Y-m-d');

        // Sunday = 7
        if ($d->format('N') == 7) {
            return true;
        }

        // Holiday
        if (isset($holidayMap[$dateStr])) {
            return true;
        }
    }

    return false;
}

$eventName = isset($_GET['eventName']) ? $_GET['eventName'] : '';
$noOfPeople = isset($_GET['noOfPeople']) ? (int) $_GET['noOfPeople'] : 0;
$fbdate = isset($_GET['fbdate']) ? $_GET['fbdate'] : null;
$tbdate = isset($_GET['tbdate']) ? $_GET['tbdate'] : null;

if (!$eventName) {
    echo json_encode([]);
    exit;
}

$useSpecial = false;

if ($fbdate && $tbdate) {
    $useSpecial = hasSundayOrHoliday($fbdate, $tbdate, $con);
}

$priceColumn = $useSpecial ? 'splPrice' : 'fPrice';

// Example query — adjust logic to match your table columns
$sql = "SELECT id, fName, gst_rate, compulsory, max_people, $priceColumn AS fPrice FROM facility 
        WHERE ((max_people = $noOfPeople AND eName = '$eventName')
        OR (eName = 'ALL'))";

$result = $con->query($sql);
$facilities = [];

while ($row = mysqli_fetch_assoc($result)) {
     $show = true;

    // Optional filter by max people
    if ($noOfPeople > 0 && $row['max_people'] > 0) {
        if ($row['max_people'] != $noOfPeople) {
            $show = false;
        }
    }

    if ($show) {
        $facilities[] = [
            'id' => $row['id'],
            'fName' => $row['fName'],
            'fPrice' => number_format((float)$row['fPrice'], 2, '.', ''),
            'gst_rate' => $row['gst_rate'],
            'compulsory' => $row['compulsory'],
            'max_people' => $row['max_people']
            //'special' => $useSpecial // frontend can show badge
        ];
    }
}


// =====================
// Return JSON
// =====================
$response = [
    'useSpecial' => $useSpecial,   // ✅ once globally
    'facilities' => $facilities   // ✅ actual data
];
echo json_encode($response);
exit;

?>
