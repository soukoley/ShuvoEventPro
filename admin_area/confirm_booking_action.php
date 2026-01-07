<?php
session_start();
include('./includes/db.php');

mysqli_begin_transaction($con);

try{

    /* ===============================
       READ JSON INPUT
    ================================ */
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid JSON data");
    }

    /* ===============================
       BASIC BOOKING DATA
    ================================ */
    $booking_id     = $data['booking_id'];
    $start_date     = $data['start_date'];
    $start_time     = $data['start_time'];
    $end_date       = $data['end_date'];
    $end_time       = $data['end_time'];
    $max_guest      = $data['max_guest'];

    $discount_type  = $data['discount_type'];
    $discount_value = $data['discount_value'];
    $payable_amount = $data['payable_amount'];
    $advance_paid   = $data['advance_paid'];
    $due_amount     = $data['due_amount'];

    $payment_type   = $data['payment_type'];
    $payment_details= $data['payment_details']; // array

    $facilities     = json_decode($data['facilities'], true);

    // update booking
    $update_bookingDetails = "UPDATE booking_details SET
            start_date='$start_date',
            start_time='$start_time',
            end_date='$end_date',
            end_time='$end_time',
            max_guest='$max_guest',
            status='Confirmed'
        WHERE booking_id='$booking_id'";
    
    if (!mysqli_query($con, $update_bookingDetails)) {
        throw new Exception("Booking details updation failed.");
    }

    // reset facilities
    $reset_facilities = "DELETE FROM booking_facilities WHERE booking_id='$booking_id' ";
    if (!mysqli_query($con, $reset_facilities)) {
        throw new Exception("Reset facilities failed.");
    }

    $total_amount = 0;

    // insert facilities
    foreach($facilities as $f){
        $f_id = $f['facility_id'];
        $qty = $f['qty'];
        $rate = $f['rate'];
        //$total = $f['total'];
        $total = $qty * $rate;
        $total_amount += $total;

        $insert_bf = "INSERT INTO booking_facilities
            (booking_id, facility_id, qty, rate, tot_amt)
            VALUES
            ('$booking_id',$f_id,$qty,$rate,$total)";

        if (!mysqli_query($con, $insert_bf)) {
            throw new Exception("Error inserting $f_id: " . mysqli_error($con));
        }
    }

    if($discount_type === '') {
        $disc_amt = 0.00;
        $disc_rate = 0.00;
    } else if($discount_type === 'flat') {
        $disc_amt = $discount_value;
        $disc_rate = 0.00;
    } else {
        $disc_amt = $total_amount - $payable_amount;
        $disc_rate = $discount_value;
    }

    $payExistQry = "SELECT * FROM payment WHERE booking_id='$booking_id'";
    $payExistRes = mysqli_query($con, $payExistQry);
    $today = date('Y-m-d');

    if ($payExistRow = mysqli_fetch_assoc($payExistRes)) {

        // ✅ Payment exists → Update
        $updPay = "UPDATE payment 
                   SET  disc_rate = $disc_rate,
                        disc_amt = $disc_amt,
                        gross_amt = $total_amount,
                        net_amt = $payable_amount,
                        adv_amt = $advance_paid,
                        due_amt = $due_amount,
                        payment_date = '$today'
                   WHERE id='$payExistRow[id]'";

        if (!mysqli_query($con, $updPay)) {
            throw new Exception("Failed to update payment: " . mysqli_error($con));
        }
    } else {

        // ❌ Payment does not exist → Insert
        $payQry = "INSERT INTO payment 
                (booking_id, sgst, sgst_amt, cgst, cgst_amt, disc_rate, disc_amt, gross_amt, net_amt, adv_amt, due_amt, payment_date)
                VALUES
                ('$booking_id', 0.00, 0.00, 0.00, 0.00, $disc_rate, $disc_amt, $total_amount, $payable_amount, $advance_paid, $due_amount, '$today')";

        if (!mysqli_query($con, $payQry)) {
            throw new Exception("Failed to insert payment: " . mysqli_error($con));
        }
    }

    /* ===============================
    SAVE PAYMENT DETAILS
    ================================ */

    $payment_date = date('Y-m-d');        // payment entry date

    $blAmt  = $payable_amount;            // Bill Amount
    $pdAmt  = $advance_paid;              // Paid Amount (Advance)
    $dueAmt = $due_amount;                // Due Amount

    $p_type = $payment_type;              // CASH / UPI / NEFT / CHEQUE

    // Default NULL values
    $transaction_id   = 'NA';
    $transaction_date = $payment_date; // today
    $neftid           = 'NA';
    $bank             = 'NA';
    $ifsc             = 'NA';
    $chk_no           = 'NA';
    $chk_dt           = $payment_date;
    $acc_no           = 'NA';
    $acc_name         = 'NA';

    /* -------- CASH -------- */
    if ($p_type === 'CASH') {

        // No extra details needed

    }

    /* -------- UPI -------- */
    elseif ($p_type === 'UPI') {

        $transaction_id   = $payment_details['transaction_ref'];
        $transaction_date = $payment_details['transaction_date'];

    }

    /* -------- NEFT -------- */
    elseif ($p_type === 'NEFT') {

        $transaction_date = $payment_details['transaction_date'];
        $neftid           = $payment_details['reference_number'];
        $bank             = $payment_details['bank_name'];
        $ifsc             = $payment_details['ifsc_code'];
        $acc_no           = $payment_details['account_number'];
        $acc_name         = $payment_details['account_name'];

    }

    /* -------- CHEQUE -------- */
    elseif ($p_type === 'CHEQUE') {

        $neftid   = $payment_details['neft_ref_no'];
        $bank     = $payment_details['bank_name'];
        $ifsc     = $payment_details['ifsc_code'];
        $acc_no   = $payment_details['account_number'];
        $acc_name = $payment_details['account_name'];
        $chk_no   = $payment_details['cheque_number'];
        $chk_dt   = $payment_details['cheque_date'];
    }

    /* ===============================
    INSERT INTO payment_details
    ================================ */

    $paySql = "
    INSERT INTO payment_details (
        booking_id,
        blAmt,
        pdAmt,
        dueAmt,
        transaction_id,
        transaction_date,
        neftid,
        bank,
        ifsc,
        chk_no,
        chk_dt,
        acc_no,
        acc_name,
        p_type,
        payment_date
    ) VALUES (
        '$booking_id',
         $blAmt,
         $pdAmt,
         $dueAmt,
        '$transaction_id',
        '$transaction_date',
        '$neftid',
        '$bank',
        '$ifsc',
        '$chk_no',
        '$chk_dt',
        '$acc_no',
        '$acc_name',
        '$p_type',
        '$payment_date'
    )";


    if (!mysqli_query($con, $paySql)) {
        throw new Exception("Payment details insert failed");
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
