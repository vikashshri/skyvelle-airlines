<?php
session_start();

if(!isset($_POST['Pay_Now'])){
    echo "<h2>Payment request not received</h2>";
    exit();
}

$no_of_pass   = $_SESSION['no_of_pass']    ?? 1;
$flight_no    = $_SESSION['flight_no']     ?? '';
$journey_date = $_SESSION['journey_date']  ?? '';
$class        = $_SESSION['class']         ?? 'economy';
$pnr          = $_SESSION['pnr']           ?? '';
$payment_id   = $_SESSION['payment_id']    ?? rand(100000000,999999999);
$total_amount = $_SESSION['total_amount']  ?? 0;
$payment_date = $_SESSION['payment_date']  ?? date('Y-m-d');
$payment_mode = $_POST['payment_mode']     ?? 'credit card';

/* card details — only populated for card payments */
$card_holder  = trim($_POST['card_holder_name'] ?? '') ?: null;
$card_number  = trim($_POST['card_number']      ?? '');
$expiry_month = trim($_POST['expiry_month']     ?? '') ?: null;
$expiry_year  = trim($_POST['expiry_year']      ?? '') ?: null;

/* store only last 4 digits for security */
$card_masked  = !empty($card_number)
                ? '************' . substr(preg_replace('/\D/','',$card_number), -4)
                : null;

/* unique transaction ID */
$transaction_id = 'TXN' . strtoupper(substr(md5(uniqid('', true)), 0, 12));
$payment_status = 'SUCCESS';

require_once('Database Connection file/mysqli_connect.php');

/* ── 1. update seats ── */
$col  = $class === 'business' ? 'seats_business' : 'seats_economy';
$stmt = mysqli_prepare($dbc,
    "UPDATE flight_details SET $col = $col - ? WHERE flight_no=? AND departure_date=?");
mysqli_stmt_bind_param($stmt, "iss", $no_of_pass, $flight_no, $journey_date);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/* ── 2. confirm ticket ── */
$stmt = mysqli_prepare($dbc,
    "UPDATE ticket_details SET booking_status='CONFIRMED', payment_id=? WHERE pnr=?");
mysqli_stmt_bind_param($stmt, "ss", $payment_id, $pnr);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

/* ── 3. insert payment record ── */
$stmt = mysqli_prepare($dbc,
    "INSERT INTO payment_details
     (payment_id, pnr, payment_date, payment_amount, payment_mode,
      card_holder_name, card_number, expiry_month, expiry_year,
      transaction_id, payment_status)
     VALUES (?,?,?,?,?,?,?,?,?,?,?)");

mysqli_stmt_bind_param(
    $stmt,
    "sssdsssssss",
    $payment_id,
    $pnr,
    $payment_date,
    $total_amount,
    $payment_mode,
    $card_holder,
    $card_masked,
    $expiry_month,
    $expiry_year,
    $transaction_id,
    $payment_status
);

$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);

if($ok){
    $_SESSION['transaction_id'] = $transaction_id;
    header("Location: ticket_success.php");
    exit();
} else {
    echo "<h2 style='color:red;font-family:sans-serif;padding:30px;'>
            Payment insert failed. Please try again.
          </h2>";
}