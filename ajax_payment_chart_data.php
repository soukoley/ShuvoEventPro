<?php
session_start();
include("includes/db.php");

$from = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to   = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$where = "";
if ($from && $to) {
    $where = " WHERE payment_date BETWEEN '$from' AND '$to'";
}

/* DAILY */
$dailySql = "
    SELECT payment_date, SUM(pdAmt) total
    FROM payment_details
    $where
    GROUP BY payment_date
    ORDER BY payment_date ASC
";
$res = mysqli_query($con, $dailySql);

$dailyLabels = [];
$dailyData = [];

while ($r = mysqli_fetch_assoc($res)) {
    $dailyLabels[] = date('d M', strtotime($r['payment_date']));
    $dailyData[] = (float)$r['total'];
}

/* MODE */
$modeSql = "
    SELECT p_type, SUM(pdAmt) total
    FROM payment_details
    $where
    GROUP BY p_type
";
$res = mysqli_query($con, $modeSql);

$modeLabels = [];
$modeData = [];

while ($r = mysqli_fetch_assoc($res)) {
    $modeLabels[] = $r['p_type'];
    $modeData[] = (float)$r['total'];
}

/* SUMMARY */
$sumSql = "
    SELECT SUM(adv_amt) received, SUM(due_amt) due
    FROM payment
";
$res = mysqli_query($con, $sumSql);
$sum = mysqli_fetch_assoc($res);

echo json_encode([
    "daily" => [
        "labels" => $dailyLabels,
        "data"   => $dailyData
    ],
    "mode" => [
        "labels" => $modeLabels,
        "data"   => $modeData
    ],
    "summary" => [
        "received" => (float)$sum['received'],
        "due"      => (float)$sum['due']
    ]
]);
