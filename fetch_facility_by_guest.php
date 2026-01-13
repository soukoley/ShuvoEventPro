<?php
include('./includes/db.php');

$max_guest = $_POST['max_guest'];
$e_name    = $_POST['e_name'];

$data = [];

$q = mysqli_query($con,"
SELECT id, fName, fPrice
FROM facility
WHERE 
    (max_people='$max_guest' AND eName='$e_name')
    OR eName='ALL'
ORDER BY fName ASC
");

while($r=mysqli_fetch_assoc($q)){
    $data[] = $r;
}

echo json_encode($data);
