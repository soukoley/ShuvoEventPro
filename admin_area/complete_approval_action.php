<?php
header('Content-Type: application/json');
session_start();
include('./includes/db.php');

mysqli_begin_transaction($con);

try{

    /* ===============================
       READ JSON INPUT
    ================================ */
    $data = json_decode(file_get_contents("php://input"), true);
    /* echo '<pre>';
    print_r($data);
    exit; */
    if (!$data) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON input'
        ]);
        exit;
    }

    /* ===============================
       BASIC BOOKING DATA
    ================================ */
    $booking_id         = $data['booking_id'];
    $received_amount    = $data['received_amount'];
    $due_amount         = $data['due_amount'];

    $payment_type       = $data['payment_type'];
    $payment_details    = $data['payment_details']; // array

    $facilities         = $data['facilities'];
    
    $url = "index.php?booking_details=1&id=" . urlencode(trim($booking_id));

    // reset facilities
    /* $reset_facilities = "DELETE FROM booking_facilities WHERE booking_id='$booking_id' ";
    if (!mysqli_query($con, $reset_facilities)) {
        throw new Exception("Reset facilities failed.");
    } */

    // Update booking status to Copmpleted
    $update_booking = "UPDATE booking_details SET status='Completed' WHERE booking_id='$booking_id' ";
    if (!mysqli_query($con, $update_booking)) {
        throw new Exception("Error updating booking status: " . mysqli_error($con));
    }

    $totalGross = 0;
    $totalGST = 0;
    $totalNet = 0;
    /* echo '<pre>';
    var_dump($facilities);
    exit; */
    // insert facilities
    foreach($facilities as $f){
        $f_id = (int) $f['facility_id'];
        $qty  = (int) ($f['qty']);

        $rate = (float) ($f['rate']);
        $taxableAmt = (float) ($f['taxableAmt']);
        $gstRate    = (float) ($f['gstRate']);
        $gstAmt     = (float) ($f['gstAmt']);
        $netAmt     = (float) ($f['netAmt']);

        $totalGross += $taxableAmt;
        $totalGST += $gstAmt;
        $totalNet += $netAmt;

        // check the row exists or not
        $check_bf = "SELECT * FROM booking_facilities WHERE booking_id='$booking_id' AND facility_id=$f_id";
        $check_bf_res = mysqli_query($con, $check_bf);
        if (mysqli_num_rows($check_bf_res) > 0) {
            // Update existing row
            $update_bf = "UPDATE booking_facilities SET qty=$qty, rate=$rate, taxableAmt=$taxableAmt, gstAmt=$gstAmt, netAmt=$netAmt WHERE booking_id='$booking_id' AND facility_id=$f_id";
            
            if(!mysqli_query($con, $update_bf)) {
                throw new Exception("Error updating $f_id: " . mysqli_error($con));
            }
        } else {
            // Insert new row
            $insert_bf = "INSERT INTO booking_facilities
                (booking_id, facility_id, qty, rate, taxableAmt, gstAmt, netAmt)
                VALUES
                ('$booking_id',$f_id,$qty,$rate,$taxableAmt,$gstAmt,$netAmt)";
            
            if (!mysqli_query($con, $insert_bf)) {
                throw new Exception("Error inserting $f_id: " . mysqli_error($con));
            }
        }
    }
    /* ===============================
    UPDATE PAYMENT TABLE   
    ================================ */
    
    $dueAmount      = (float) $data['due_amount'];
    $advanceAmount  = (float) $data['advance_amount'];
    $discountAmount = (float) $data['discount_amount'];
    $grossAmount    = (float) $data['final_amount'];
    $receivedAmount = (float) $data['received_amount'];
    $netAmount      = $grossAmount - $discountAmount;

    $payExistQry = "SELECT * FROM payment WHERE booking_id='$booking_id'";
    $payExistRes = mysqli_query($con, $payExistQry);
    $today = date('Y-m-d');
    
    if ($payExistRow = mysqli_fetch_assoc($payExistRes)) {

        // ✅ Payment exists → Update
        $updPay = "UPDATE payment 
                   SET  gross_amt = $grossAmount,
                        net_amt = $netAmount,
                        adv_amt = adv_amt + $receivedAmount,
                        due_amt = $dueAmount,
                        payment_date = '$today'
                   WHERE id='$payExistRow[id]'";
        /* echo '<pre>';
        var_dump($updPay);
        exit; */
        
        if (!mysqli_query($con, $updPay)) {
            throw new Exception("Failed to update payment: " . mysqli_error($con));
        }
    } else {
        throw new Exception("No payment found for the booking id: " . mysqli_error($con));
    }

    /* ===============================
    SAVE PAYMENT DETAILS
    ================================ */

    $payment_date = date('Y-m-d');        // payment entry date
    $pdAmt  = $receivedAmount;         // Paid Amount
    $p_type = $payment_type;              // CASH / UPI / NEFT / CHEQUE

    /* -------- CASH -------- */
    if ($p_type === 'CASH') {

        // No extra details needed

    }

    /* -------- UPI -------- */
    elseif ($p_type === 'UPI') {

        $transaction_id   = mysqli_real_escape_string($con, $payment_details['transaction_ref']);
        $transaction_date = $payment_details['transaction_date'];
        $transaction_date  = date('Y-m-d', strtotime($transaction_date));

    }

    /* -------- NEFT -------- */
    elseif ($p_type === 'NEFT') {

        $neftid           = mysqli_real_escape_string($con, $payment_details['reference_number']);
        $bank             = mysqli_real_escape_string($con, $payment_details['bank_name']);
        $ifsc             = mysqli_real_escape_string($con, $payment_details['ifsc_code']);
        $acc_no           = mysqli_real_escape_string($con, $payment_details['account_number']);
        $acc_name         = mysqli_real_escape_string($con, $payment_details['account_name']);
        $transaction_date = $payment_details['transaction_date'];
        $transaction_date = date('Y-m-d', strtotime($transaction_date));
        
    }

    /* -------- CHEQUE -------- */
    elseif ($p_type === 'CHEQUE') {

        $neftid   = mysqli_real_escape_string($con, $payment_details['neft_ref_no']);
        $bank     = mysqli_real_escape_string($con, $payment_details['bank_name']);
        $ifsc     = mysqli_real_escape_string($con, $payment_details['ifsc_code']);
        $acc_no   = mysqli_real_escape_string($con, $payment_details['account_number']);
        $acc_name = mysqli_real_escape_string($con, $payment_details['account_name']);
        $chk_no   = mysqli_real_escape_string($con, $payment_details['cheque_number']);
        $chk_dt   = $payment_details['cheque_date'];
        $chk_dt   = date('Y-m-d', strtotime($chk_dt));
    } else {
        throw new Exception("Invalid payment type");
    }

    /* ===============================
    INSERT INTO payment_details
    ================================ */

    if($p_type == "CHEQUE") {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt, neftid, 
                bank, ifsc, chk_no, chk_dt, acc_no, acc_name, p_type, payment_date)
        VALUES ('$booking_id', $pdAmt, '$neftid', '$bank', '$ifsc', '$chk_no', '$chk_dt', 
                '$acc_no', '$acc_name', '$p_type', '$payment_date')";

    } else if($p_type == "NEFT") {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt, 
        transaction_date, neftid, bank, ifsc, acc_no, acc_name, p_type, payment_date)
        VALUES ('$booking_id', $pdAmt, '$transaction_date', '$neftid', '$bank', '$ifsc', 
                '$acc_no', '$acc_name', '$p_type', '$payment_date')";

    } else if($p_type == "UPI") {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt, 
        transaction_id, transaction_date, p_type, payment_date)
        VALUES ('$booking_id', $pdAmt, 
                '$transaction_id', '$transaction_date', '$p_type', '$payment_date')";
    } else {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt, p_type, payment_date)
        VALUES ('$booking_id', $pdAmt, '$p_type', '$payment_date')";  // For cash or other modes

    }
    
    if (!mysqli_query($con, $insert_payment_details)) {
        $_SESSION['error'] = "Error inserting payment details: " . mysqli_error($con);
        mysqli_rollback($con); // 🔁 Roll back all changes
        header("Location: $url");
        exit();
    }

    mysqli_commit($con);

    echo json_encode([
        "status"=>"OK",
        'success'   => true,
        'booking_id'   => $booking_id,
    ]);

}catch(Exception $e){
    mysqli_rollback($con);
    //http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Transaction failed: ' . $e->getMessage()
    ]);
}
