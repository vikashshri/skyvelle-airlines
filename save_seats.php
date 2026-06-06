<?php
session_start();

if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

if(!isset($_POST['seats']) || !isset($_POST['pnr'])){
    header("Location: select_seats.php");
    exit();
}

$pnr        = $_POST['pnr'];
$seats_raw  = trim($_POST['seats']);
$customer_id = $_SESSION['userid'];
$no_of_pass = $_SESSION['no_of_pass'] ?? 1;

$seats = array_filter(array_map('trim', explode(',', $seats_raw)));

if(count($seats) != $no_of_pass){
    header("Location: select_seats.php");
    exit();
}

require_once('Database Connection file/mysqli_connect.php');

/* ── assign seats to passengers in order ── */
$pq = "SELECT passenger_id FROM passengers WHERE pnr = ? ORDER BY passenger_id ASC";
$pst = mysqli_prepare($dbc, $pq);
mysqli_stmt_bind_param($pst, "s", $pnr);
mysqli_stmt_execute($pst);
$pres = mysqli_stmt_get_result($pst);
$passenger_ids = [];
while($row = mysqli_fetch_assoc($pres)) $passenger_ids[] = $row['passenger_id'];
mysqli_stmt_close($pst);

$uq = "UPDATE passengers SET seat_no = ? WHERE passenger_id = ?";
$ust = mysqli_prepare($dbc, $uq);

$success = true;
foreach($passenger_ids as $idx => $pid){
    $seat = $seats[$idx] ?? '';
    mysqli_stmt_bind_param($ust, "si", $seat, $pid);
    if(!mysqli_stmt_execute($ust)) $success = false;
}
mysqli_stmt_close($ust);
mysqli_close($dbc);

/* ── store in session for receipt ── */
$_SESSION['selected_seats'] = $seats;

/* ── go to payment ── */
header("Location: payment_details.php");
exit();
