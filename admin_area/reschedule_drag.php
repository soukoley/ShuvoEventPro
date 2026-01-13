<?php
include("includes/db.php");

$id    = intval($_POST['booking_id']);
$start = $_POST['start'];
$end   = $_POST['end'] ?? $start;

// 🔒 Conflict check
$chk = $con->prepare("
    SELECT id FROM booking_details 
    WHERE event_date=? AND id!=? AND status!='Cancelled'
");
$chk->bind_param("si",$start,$id);
$chk->execute();
$chk->store_result();

if($chk->num_rows > 0){
    echo "Another booking already exists on this date.";
    exit;
}

// ✅ Update
$upd = $con->prepare("
    UPDATE booking_details 
    SET event_date=?, event_end=? 
    WHERE id=?
");
$upd->bind_param("ssi",$start,$end,$id);

if($upd->execute()){
    echo "OK";
}else{
    echo "Database error";
}
