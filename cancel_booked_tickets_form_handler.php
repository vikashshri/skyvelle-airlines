<?php
session_start();

if(!isset($_SESSION['userid'])){
    die("Customer not logged in.");
}

if(!isset($_POST['Cancel_Ticket'])){
    echo "Cancel request not received.";
    exit();
}

if(empty($_POST['pnr'])){
    echo "PNR is required.";
    exit();
}

$pnr         = trim($_POST['pnr']);
$reason      = trim($_POST['reason'] ?? $_SESSION['cancel_reason'] ?? 'Not specified');
$customer_id = $_SESSION['userid'];   // ← fixed
$todays_date = date('Y-m-d');

require_once('Database Connection file/mysqli_connect.php');

/* ── verify ticket exists and belongs to this customer ── */

$query = "SELECT COUNT(*)
          FROM ticket_details
          WHERE pnr=?
          AND customer_id=?
          AND journey_date>=?";

$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, "sss", $pnr, $customer_id, $todays_date);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $cnt);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if($cnt != 1){
    mysqli_close($dbc);
    header("Location: cancel_booked_tickets.php?msg=failed");
    exit();
}

/* ── cancel the ticket ── */

$query = "UPDATE ticket_details
          SET booking_status='CANCELLED'
          WHERE pnr=?
          AND customer_id=?";

$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, "ss", $pnr, $customer_id);

if(!mysqli_stmt_execute($stmt)){
    die("Cancellation failed: " . mysqli_stmt_error($stmt));
}

$affected = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

if($affected != 1){
    mysqli_close($dbc);
    header("Location: cancel_booked_tickets.php?msg=failed");
    exit();
}

/* ── save cancellation reason ── */
$rq = "UPDATE ticket_details SET cancellation_reason=? WHERE pnr=?";
$rst = mysqli_prepare($dbc, $rq);
mysqli_stmt_bind_param($rst, "ss", $reason, $pnr);
mysqli_stmt_execute($rst);
mysqli_stmt_close($rst);

/* ── get refund + flight details ── */

$query = "SELECT t.flight_no, t.journey_date, t.no_of_passengers, t.class,
                 (0.85 * p.payment_amount) AS refund_amount
          FROM ticket_details t
          LEFT JOIN payment_details p ON t.pnr = p.pnr
          WHERE t.pnr=?";

$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, "s", $pnr);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $flight_no, $journey_date, $no_of_pass, $class, $refund_amount);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$_SESSION['refund_amount'] = $refund_amount ?? 0;

/* ── restore seats to flight ── */

if(strtolower($class) == 'economy'){
    $q = "UPDATE flight_details
          SET seats_economy = seats_economy + ?
          WHERE flight_no=? AND departure_date=?";
} else {
    $q = "UPDATE flight_details
          SET seats_business = seats_business + ?
          WHERE flight_no=? AND departure_date=?";
}

$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "iss", $no_of_pass, $flight_no, $journey_date);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_close($dbc);

header("Location: cancel_booked_tickets_success.php");
exit();