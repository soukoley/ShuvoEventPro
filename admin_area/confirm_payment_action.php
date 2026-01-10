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
    $final_amount       = $data['final_amount'];
    $discount_amount    = $data['discount_amount'];
    $advance_amount     = $data['advance_amount'];
    $received_amount    = $data['received_amount'];
    $due_amount         = $data['due_amount'];
    $payment_type       = $data['payment_type'];
    $payment_details    = $data['payment_details']; // array

    $netAmount       = floatval($final_amount) - floatval($discount_amount) - floatval($advance_amount);
    
    $url = "index.php?receive_Payment=0&id=" . urlencode(trim($booking_id));

    $payExistQry = "SELECT * FROM payment WHERE booking_id='$booking_id'";
    $payExistRes = mysqli_query($con, $payExistQry);
    $payment_date = date('Y-m-d');
    
    if ($payExistRow = mysqli_fetch_assoc($payExistRes)) {

        // ✅ Payment exists → Update
        $updPay = "UPDATE payment 
                   SET  gross_amt = $final_amount,
                        net_amt = $netAmount,
                        due_amt = $due_amount,
                        adv_amt = adv_amt + $advance_amount,
                        payment_date = '$payment_date'
                   WHERE id='$payExistRow[id]'";

        if (!mysqli_query($con, $updPay)) {
            throw new Exception("Failed to update payment: " . mysqli_error($con));
        }
    } else {
        throw new Exception("Failed to insert payment: " . mysqli_error($con));
    }

    /* ===============================
    SAVE PAYMENT DETAILS
    ================================ */

    $pdAmt  = $received_amount;         // Paid Amount
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
        VALUES ('$booking_id', $pdAmt, '$neftid', 
                '$bank', '$ifsc', '$chk_no', '$chk_dt', 
                '$acc_no', '$acc_name', '$p_type', '$payment_date')";

    } else if($p_type == "NEFT") {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt,  
        transaction_date, neftid, bank, ifsc, acc_no, acc_name, p_type, payment_date)
        VALUES ('$booking_id', $pdAmt,  
                '$transaction_date', '$neftid', '$bank', '$ifsc', 
                '$acc_no', '$acc_name', '$p_type', '$payment_date')";

    } else if($p_type == "UPI") {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt, 
        transaction_id, transaction_date, p_type, payment_date)
        VALUES ('$booking_id', $pdAmt, 
                '$transaction_id', '$transaction_date', '$p_type', '$payment_date')";
    } else {

        $insert_payment_details = "INSERT INTO payment_details (booking_id, pdAmt, 
        p_type, payment_date)
        VALUES ('$booking_id', $pdAmt, '$p_type', '$payment_date')";  // For cash or other modes

    }
    //$executed_queries[] = $insert_payment_details;
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
