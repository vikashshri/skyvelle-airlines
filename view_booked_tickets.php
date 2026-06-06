<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Booked Tickets — Skyvelle</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
	margin:0;
	padding:0;
	box-sizing:border-box;
	font-family:'Poppins',sans-serif;
}

body{
	background:
	linear-gradient(rgba(3,8,25,0.92),
	rgba(3,8,25,0.95)),
	url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
	background-size:cover;
	background-position:center;
	background-attachment:fixed;
	color:white;
	min-height:100vh;
	padding:30px;
}

/* NAVBAR */

.navbar{
	display:flex;
	justify-content:space-between;
	align-items:center;
	padding:18px 30px;
	margin-bottom:30px;
}

.logo h1{
	font-family:'Cormorant Garamond',serif;
	font-size:52px;
	font-weight:700;
	letter-spacing:1px;
	color:white;
	line-height:1;
}
.nav-links{ display:flex; gap:15px; flex-wrap:wrap; }

.nav-links a{
	text-decoration:none;
	color:white;
	padding:12px 18px;
	border-radius:12px;
	transition:0.3s;
}

.nav-links a:hover{
	background:linear-gradient(135deg,#4361ee,#7209b7);
	transform:translateY(-2px);
}

/* PAGE TITLE */

.page-title{
	background:linear-gradient(135deg,#1d4ed8,#7e22ce);
	border-radius:26px;
	padding:36px 44px;
	margin-bottom:30px;
}

.page-title h2{
	font-family:'Cormorant Garamond',serif;
	font-size:48px;
	font-weight:700;
	margin-bottom:8px;
}

.page-title p{
	color:#bfdbfe;
	font-size:15px;
}

/* SECTION LABEL */

.section-label{
	font-size:20px;
	font-weight:600;
	margin-bottom:18px;
	display:flex;
	align-items:center;
	gap:10px;
	color:#dbeafe;
}

.section-label i{ color:#4cc9f0; }

/* TICKET CARD */

.ticket-card{
	background:rgba(255,255,255,0.08);
	backdrop-filter:blur(20px);
	border:1px solid rgba(255,255,255,0.09);
	border-radius:22px;
	padding:24px 28px;
	margin-bottom:16px;
	display:flex;
	align-items:center;
	gap:24px;
	transition:0.3s;
}

.ticket-card{ cursor:pointer; }
.ticket-card-link{ text-decoration:none; color:inherit; display:block; }
.ticket-card:hover{
	transform:translateY(-4px);
	box-shadow:0 10px 25px rgba(0,0,0,0.3);
	background:rgba(255,255,255,0.1);
}

.ticket-icon{
	width:52px;
	height:52px;
	border-radius:14px;
	background:rgba(76,201,240,0.15);
	display:flex;
	align-items:center;
	justify-content:center;
	flex-shrink:0;
}

.ticket-icon i{
	font-size:22px;
	color:#4cc9f0;
}

.ticket-main{ flex:1; }

.ticket-top{
	display:flex;
	align-items:center;
	gap:14px;
	margin-bottom:8px;
	flex-wrap:wrap;
}

.ticket-top .pnr{
	font-size:20px;
	font-weight:700;
	color:#ffffff;
	letter-spacing:1px;
}

.ticket-top .flight{
	font-size:14px;
	color:#94a3b8;
}

.ticket-details{
	display:flex;
	gap:20px;
	flex-wrap:wrap;
}

.detail-chip{
	display:flex;
	align-items:center;
	gap:6px;
	font-size:13px;
	color:#cbd5e1;
}

.detail-chip i{ color:#4cc9f0; font-size:12px; }

/* STATUS BADGES */

.badge{
	font-size:12px;
	font-weight:700;
	padding:5px 14px;
	border-radius:20px;
	white-space:nowrap;
	flex-shrink:0;
}

.badge-confirmed{ background:rgba(34,197,94,0.15);  color:#86efac; border:1px solid rgba(34,197,94,0.3); }
.badge-pending{   background:rgba(234,179,8,0.15);   color:#fde68a; border:1px solid rgba(234,179,8,0.3); }
.badge-cancelled{ background:rgba(239,68,68,0.15);   color:#fca5a5; border:1px solid rgba(239,68,68,0.3); }
.badge-default{   background:rgba(255,255,255,0.08); color:#cbd5e1; border:1px solid rgba(255,255,255,0.1); }

/* EMPTY STATE */

.empty-state{
	background:rgba(255,255,255,0.05);
	border:1px dashed rgba(255,255,255,0.12);
	border-radius:20px;
	padding:36px;
	text-align:center;
	color:#64748b;
	margin-bottom:16px;
}

.empty-state i{
	font-size:36px;
	margin-bottom:12px;
	color:#334155;
}

/* SECTION WRAPPER */

.section-wrap{ margin-bottom:36px; }

/* RESPONSIVE */

@media(max-width:768px){
	.navbar{ flex-direction:column; gap:15px; }
	.ticket-card{ flex-direction:column; align-items:flex-start; }
	.page-title{ padding:28px 24px; }
}

</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
	<div class="logo">
		<h1>✈  Skyvelle</h1>
	</div>
	<div class="nav-links">
		<a href="homepage.php"><i class="fa-solid fa-house"></i> Home</a>
		<a href="profile2.php"><i class="fa-solid fa-user"></i> Profile</a>
		<a href="logout_handler.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
	</div>
</div>

<!-- PAGE TITLE -->
<div class="page-title">
	<h2><i class="fa-solid fa-ticket"></i> My Booked Tickets</h2>
	<p>View all your upcoming and past flight reservations</p>
</div>

<?php

$todays_date = date('Y-m-d');

$thirty_days_before = date('Y-m-d', strtotime('-30 days'));

/* ── session key fixed ── */
$customer_id = $_SESSION['userid'];

require_once('Database Connection file/mysqli_connect.php');

/* helper: badge class */
function badge_class($status){
	$s = strtolower($status);
	if(str_contains($s,'confirm')) return 'badge-confirmed';
	if(str_contains($s,'pend'))    return 'badge-pending';
	if(str_contains($s,'cancel'))  return 'badge-cancelled';
	return 'badge-default';
}

/* ════════════════════════════
   UPCOMING TRIPS
   journey_date >= today
   all booking statuses shown
════════════════════════════ */

echo '<div class="section-wrap">';
$total_query = "SELECT COUNT(*) FROM ticket_details WHERE customer_id=?";
$total_stmt = mysqli_prepare($dbc,$total_query);
mysqli_stmt_bind_param($total_stmt,"s",$customer_id);
mysqli_stmt_execute($total_stmt);
mysqli_stmt_bind_result($total_stmt,$total_tickets);
mysqli_stmt_fetch($total_stmt);
mysqli_stmt_close($total_stmt);

echo '
<div style="
background:rgba(255,255,255,0.07);
border:1px solid rgba(255,255,255,0.08);
padding:20px 25px;
border-radius:18px;
margin-bottom:25px;
font-size:18px;
font-weight:600;">
<i class="fa-solid fa-ticket" style="color:#4cc9f0;"></i>
 Total Reservations: '.$total_tickets.'
</div>';
echo '<div class="section-label">
        <i class="fa-solid fa-plane-departure"></i>
        Upcoming Trips
      </div>';

$query = "SELECT pnr, date_of_reservation, flight_no, journey_date,
                 class, booking_status, no_of_passengers, payment_id
          FROM ticket_details
          WHERE customer_id = ?
          AND journey_date >= ?
          ORDER BY journey_date ASC";

$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, "ss", $customer_id, $todays_date);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,
	$pnr, $date_of_res, $flight_no, $journey_date,
	$class, $booking_status, $no_of_pass, $payment_id);
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) == 0)
{
	echo '<div class="empty-state">
			<i class="fa-solid fa-plane-circle-xmark"></i>
			<p>No upcoming trips found.</p>
		  </div>';
}
else
{
	while(mysqli_stmt_fetch($stmt))
	{
		$bc = badge_class($booking_status);
		echo '
		<a href="ticket_receipt.php?pnr=' . urlencode($pnr) . '" class="ticket-card-link">
		<div class="ticket-card">
			<div class="ticket-icon">
				<i class="fa-solid fa-plane"></i>
			</div>
			<div class="ticket-main">
				<div class="ticket-top">
					<span class="pnr">PNR: ' . htmlspecialchars($pnr) . '</span>
					<span class="flight">Flight ' . htmlspecialchars($flight_no) . '</span>
				</div>
				<div class="ticket-details">
					<div class="detail-chip">
						<i class="fa-regular fa-calendar"></i>
						Journey: ' . htmlspecialchars($journey_date) . '
					</div>
					<div class="detail-chip">
						<i class="fa-solid fa-chair"></i>
						' . ucfirst(htmlspecialchars($class)) . '
					</div>
					<div class="detail-chip">
						<i class="fa-solid fa-users"></i>
						' . htmlspecialchars($no_of_pass) . ' Passenger(s)
					</div>
					<div class="detail-chip">
						<i class="fa-solid fa-receipt"></i>
						Booked: ' . htmlspecialchars($date_of_res) . '
					</div>
					' . ($payment_id ? '<div class="detail-chip"><i class="fa-solid fa-credit-card"></i> Pay ID: ' . htmlspecialchars($payment_id) . '</div>' : '') . '
				</div>
			</div>
			<div class="badge ' . $bc . '">' . htmlspecialchars($booking_status) . '</div>
		</div>
		</a>';
	}
}

mysqli_stmt_close($stmt);
echo '</div>';

/* ════════════════════════════
   PAST TRIPS
   journey_date < today
   within last 30 days
════════════════════════════ */

echo '<div class="section-wrap">';
echo '<div class="section-label">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Past Trips (Last 30 Days)
      </div>';

$query = "SELECT pnr, date_of_reservation, flight_no, journey_date,
                 class, booking_status, no_of_passengers, payment_id
          FROM ticket_details
          WHERE customer_id = ?
          AND journey_date < ?
          AND journey_date >= ?
          ORDER BY journey_date DESC";

$stmt = mysqli_prepare($dbc, $query);
mysqli_stmt_bind_param($stmt, "sss", $customer_id, $todays_date, $thirty_days_before);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,
	$pnr, $date_of_res, $flight_no, $journey_date,
	$class, $booking_status, $no_of_pass, $payment_id);
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) == 0)
{
	echo '<div class="empty-state">
			<i class="fa-solid fa-calendar-xmark"></i>
			<p>No trips completed in the past 30 days.</p>
		  </div>';
}
else
{
	while(mysqli_stmt_fetch($stmt))
	{
		$bc = badge_class($booking_status);
		echo '
		<a href="ticket_receipt.php?pnr=' . urlencode($pnr) . '" class="ticket-card-link">
		<div class="ticket-card">
			<div class="ticket-icon">
				<i class="fa-solid fa-plane-arrival"></i>
			</div>
			<div class="ticket-main">
				<div class="ticket-top">
					<span class="pnr">PNR: ' . htmlspecialchars($pnr) . '</span>
					<span class="flight">Flight ' . htmlspecialchars($flight_no) . '</span>
				</div>
				<div class="ticket-details">
					<div class="detail-chip">
						<i class="fa-regular fa-calendar"></i>
						Journey: ' . htmlspecialchars($journey_date) . '
					</div>
					<div class="detail-chip">
						<i class="fa-solid fa-chair"></i>
						' . ucfirst(htmlspecialchars($class)) . '
					</div>
					<div class="detail-chip">
						<i class="fa-solid fa-users"></i>
						' . htmlspecialchars($no_of_pass) . ' Passenger(s)
					</div>
					<div class="detail-chip">
						<i class="fa-solid fa-receipt"></i>
						Booked: ' . htmlspecialchars($date_of_res) . '
					</div>
					' . ($payment_id ? '<div class="detail-chip"><i class="fa-solid fa-credit-card"></i> Pay ID: ' . htmlspecialchars($payment_id) . '</div>' : '') . '
				</div>
			</div>
			<div class="badge ' . $bc . '">' . htmlspecialchars($booking_status) . '</div>
		</div>
		</a>';
	}
}

mysqli_stmt_close($stmt);
mysqli_close($dbc);

echo '</div>';

?>

</body>
</html>