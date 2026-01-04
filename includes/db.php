<?php
// Database Configuration
$db_host = 'localhost';
$db_user = 'root';  // Change this as per your setup
$db_pass = '';      // Change this as per your setup
$db_name = 'greenland';

// Create Connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check Connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// ===================== CURRENT FINANCIAL YEAR =====================
$c_fyear_qry = "SELECT f_year FROM finance_year WHERE status=1";
$c_fyear_res = mysqli_query($con, $c_fyear_qry);
$c_fyear_row = mysqli_fetch_array($c_fyear_res);
$cur_finance_year = $c_fyear_row['f_year'];

// ===================== COMPANY SHORT NAME =====================
$shortNameQry = "SELECT abbreviation FROM admins LIMIT 1";
$shortNameRes = $con->query($shortNameQry);
if ($rowShort = mysqli_fetch_assoc($shortNameRes)) {
    $companyShortName = $rowShort['abbreviation'];
} else {
    $companyShortName = "CMP"; // Default short name
}


// Set charset to UTF-8
mysqli_set_charset($con, "utf8");
?>