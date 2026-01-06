<?php
include('./includes/db.php');
$hall=$_GET['hall'] ?? '';

$where="";
if($hall!="") $where="AND bd.e_name='$hall'";

$q=mysqli_query($con,"
SELECT bd.*,c.c_name
FROM booking_details bd
JOIN customer c ON bd.cust_id=c.c_id
WHERE bd.booking_status!='Cancelled' $where
");

$data=[];
while($r=mysqli_fetch_assoc($q)){
    $color='#3788d8';
    if($r['booking_status']=='Partially Paid') $color='#f0ad4e';
    if($r['booking_status']=='Fully Paid') $color='#5cb85c';
    if($r['booking_status']=='Rescheduled') $color='#5bc0de';

    $data[]=[
        "id"=>$r['booking_id'],
        "title"=>$r['e_name'],
        "start"=>$r['start_date'].'T'.$r['start_time'],
        "end"=>$r['end_date'].'T'.$r['end_time'],
        "backgroundColor"=>$color,
        "extendedProps"=>[
            "customer"=>$r['c_name'],
            "status"=>$r['booking_status'],
            "max_guest"=>$r['max_guest']
        ]
    ];
}
echo json_encode($data);
