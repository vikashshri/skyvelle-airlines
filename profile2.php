<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile — Skyvelle</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

:root{
    --font-heading: 'Playfair Display', serif;
    --font-body: 'Poppins', sans-serif;

    --gold: #c9a84c;
    --gold-light: #e8c97a;
}

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

.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 30px;
    margin-bottom:30px;
}

.logo h1{
    font-family:var(--font-heading);
    font-size:34px;
    font-weight:600;
    letter-spacing:1px;
    color:white;
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


/* HEADER */

.profile-header{
	background:linear-gradient(135deg,#1d4ed8,#7e22ce);
	border-radius:30px;
	padding:50px;
	display:flex;
	align-items:center;
	gap:40px;
	margin-bottom:30px;
	position:relative;
	overflow:hidden;
}

.profile-header::before{
	content:'';
	position:absolute;
	top:-60px; right:-60px;
	width:260px; height:260px;
	border-radius:50%;
	border:40px solid rgba(255,255,255,0.07);
	pointer-events:none;
}

.avatar-wrap{ position:relative; flex-shrink:0; }

.avatar{
	width:130px; height:130px;
	border-radius:50%;
	background:rgba(255,255,255,0.15);
	border:4px solid rgba(255,255,255,0.35);
	display:flex;
	align-items:center;
	justify-content:center;
	font-size:52px;
	font-weight:700;
}

.ff-badge{
    position:absolute;
    bottom:2px;
    right:2px;
    background:linear-gradient(135deg,#c9a84c,#e8c97a);
    color:#0a1628;
    font-size:11px;
    font-weight:700;
    padding:6px 12px;
    border-radius:20px;
    box-shadow:0 4px 15px rgba(201,168,76,0.4);
}

.profile-info h2{
    font-family:var(--font-heading);
    font-size:46px;
    font-weight:600;
    letter-spacing:0.5px;
    margin-bottom:8px;
    color:white;
}
.handle{ color:#bfdbfe; font-size:15px; margin-bottom:16px; }

.profile-meta{ display:flex; gap:16px; flex-wrap:wrap; }

.meta-item{
	display:flex;
	align-items:center;
	gap:8px;
	background:rgba(255,255,255,0.12);
	padding:8px 16px;
	border-radius:10px;
	font-size:14px;
}

.meta-item i{ color:#93c5fd; }

.edit-btn{
	display:inline-flex;
	align-items:center;
	gap:8px;
	background:rgba(255,255,255,0.15);
	color:white;
	border:1px solid rgba(255,255,255,0.25);
	padding:12px 24px;
	border-radius:14px;
	font-size:14px;
	font-weight:500;
	cursor:pointer;
	transition:0.3s;
	text-decoration:none;
	margin-left:auto;
	align-self:flex-start;
	flex-shrink:0;
}

.edit-btn:hover{ background:rgba(255,255,255,0.22); transform:translateY(-2px); }

/* GRID */

.page-grid{
	display:grid;
	grid-template-columns:340px 1fr;
	gap:28px;
	margin-bottom:28px;
}

.glass-card{
	background:rgba(255,255,255,0.07);
	backdrop-filter:blur(16px);
	border:1px solid rgba(255,255,255,0.09);
	border-radius:26px;
	padding:30px;
}

.card-title{
    font-family:var(--font-heading);
    font-size:24px;
    font-weight:600;
    margin-bottom:22px;
    display:flex;
    align-items:center;
    gap:10px;
    color:white;
}

.card-title i{ color:#4cc9f0; }

/* DETAIL ROWS */

.detail-row{
	display:flex;
	align-items:center;
	gap:14px;
	padding:14px 0;
	border-bottom:1px solid rgba(255,255,255,0.07);
}

.detail-row:last-child{ border-bottom:none; }

.detail-icon{
	width:38px; height:38px;
	border-radius:10px;
	background:rgba(76,201,240,0.15);
	display:flex;
	align-items:center;
	justify-content:center;
	flex-shrink:0;
}
html{
    scroll-behavior:smooth;
}
.detail-icon i{ color:#4cc9f0; font-size:16px; }

.detail-label{ font-size:12px; color:#94a3b8; margin-bottom:2px; }

.detail-value{ font-size:15px; font-weight:500; word-break:break-all; }

/* MILEAGE */

.mileage-numbers{
	display:flex;
	justify-content:space-between;
	align-items:baseline;
	margin-bottom:8px;
}

.mileage-numbers .big{
    font-family:var(--font-heading);
    font-size:40px;
    font-weight:600;
    color:var(--gold-light);

}.mileage-numbers .sub{ font-size:13px; color:#94a3b8; }

.progress-track{
	height:10px;
	border-radius:99px;
	background:rgba(255,255,255,0.1);
	overflow:hidden;
	margin-bottom:8px;
}

.progress-fill{
	height:100%;
	border-radius:99px;
	background:linear-gradient(90deg,#4cc9f0,#4361ee);
}

.next-tier{ font-size:12px; color:#94a3b8; margin-bottom:22px; }
.next-tier span{ color:#f4b183; font-weight:600; }

/* MINI STATS */

.mini-stats{
	display:grid;
	grid-template-columns:1fr 1fr;
	gap:14px;
}

.mini-stat{
	background:rgba(255,255,255,0.06);
	border:1px solid rgba(255,255,255,0.07);
	border-radius:16px;
	padding:18px;
	text-align:center;
}

.mini-stat i{ font-size:24px; color:#4cc9f0; margin-bottom:8px; }
.mini-stat .val{ font-size:22px; font-weight:700; margin-bottom:2px; }
.mini-stat .lbl{ font-size:12px; color:#94a3b8; }

/* TICKETS */

.ticket-row{
	display:flex;
	align-items:center;
	gap:16px;
	padding:16px 0;
	border-bottom:1px solid rgba(255,255,255,0.06);
}

.ticket-row:last-child{ border-bottom:none; }

.ticket-icon{
	width:42px; height:42px;
	border-radius:12px;
	background:rgba(76,201,240,0.12);
	display:flex;
	align-items:center;
	justify-content:center;
	flex-shrink:0;
}

.ticket-icon i{ color:#4cc9f0; }

.ticket-info{ flex:1; }

.ticket-top{
	display:flex;
	align-items:center;
	gap:10px;
	font-weight:600;
	font-size:15px;
	margin-bottom:4px;
}

.ticket-top i{ color:#4cc9f0; font-size:12px; }

.ticket-sub{ font-size:12px; color:#94a3b8; display:flex; gap:14px; flex-wrap:wrap; }

.bstatus{
	font-size:12px;
	font-weight:600;
	padding:5px 12px;
	border-radius:20px;
	white-space:nowrap;
}

.bs-confirmed{ background:rgba(34,197,94,0.15);  color:#86efac; }
.bs-pending{   background:rgba(76,201,240,0.15);  color:#7dd3fc; }
.bs-cancelled{ background:rgba(239,68,68,0.15);   color:#fca5a5; }
.bs-default{   background:rgba(255,255,255,0.1);  color:#cbd5e1; }

/* PREFS */

.pref-row{
	display:flex;
	justify-content:space-between;
	align-items:center;
	padding:14px 0;
	border-bottom:1px solid rgba(255,255,255,0.07);
}

.pref-row:last-child{ border-bottom:none; }

.pref-label{ display:flex; align-items:center; gap:12px; font-size:14px; }
.pref-label i{ color:#4cc9f0; }

.toggle{
	width:44px; height:24px;
	border-radius:99px;
	background:linear-gradient(135deg,#4361ee,#7209b7);
	position:relative;
	cursor:pointer;
	flex-shrink:0;
}

.toggle::after{
	content:'';
	position:absolute;
	top:3px; right:3px;
	width:18px; height:18px;
	border-radius:50%;
	background:white;
}

.toggle.off{ background:rgba(255,255,255,0.15); }
.toggle.off::after{ right:auto; left:3px; }

/* RESPONSIVE */

@media(max-width:960px){
	.page-grid{ grid-template-columns:1fr; }
	.profile-header{ flex-direction:column; text-align:center; }
	.profile-meta{ justify-content:center; }
	.edit-btn{ margin-left:0; }
}

@media(max-width:600px){
	body{ padding:15px; }
	.profile-header{ padding:30px 20px; }
	.profile-info h2{ font-size:28px; }
	.navbar{ flex-direction:column; gap:15px; }
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
<a href="homepage.php#quick-actions">Dashboard</a>
		<a href="book_tickets.php">Book</a>
		<a href="view_booked_tickets.php">My Trips</a>
	</div>
	<div class="profile-btn">
		<i class="fa-solid fa-user"></i>&nbsp; My Profile
	</div>
</div>

<?php

require_once('Database Connection file/mysqli_connect.php');

/* ── Customer + Frequent Flyer ── */

$q = "SELECT c.name, c.email, c.phone_no, c.address,
             f.frequent_flier_no, f.mileage
      FROM customer c
      LEFT JOIN frequent_flier_details f ON f.customer_id = c.customer_id
      WHERE c.customer_id = ?";

$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['userid']);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $email, $phone, $address, $ff_no, $mileage);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

/* ── Last 4 tickets ── */

$tq = "SELECT pnr, flight_no, journey_date, class, booking_status, no_of_passengers
       FROM ticket_details
       WHERE customer_id = ?
       ORDER BY journey_date DESC
       LIMIT 4";

$tstmt = mysqli_prepare($dbc, $tq);
mysqli_stmt_bind_param($tstmt, "s", $_SESSION['userid']);
mysqli_stmt_execute($tstmt);
$tickets_result = mysqli_stmt_get_result($tstmt);

/* ── Total bookings count ── */

$cq  = "SELECT COUNT(*) FROM ticket_details WHERE customer_id = ?";
$cst = mysqli_prepare($dbc, $cq);
mysqli_stmt_bind_param($cst, "s", $_SESSION['userid']);
mysqli_stmt_execute($cst);
mysqli_stmt_bind_result($cst, $total_bookings);
mysqli_stmt_fetch($cst);
mysqli_stmt_close($cst);


/* ── Derived ── */

$mileage      = $mileage ?? 0;
$mileage_next = 50000;
$pct          = min(100, ($mileage_next > 0 ? round(($mileage / $mileage_next) * 100) : 0));
$has_ff       = !empty($ff_no);
$initials     = strtoupper(substr($name ?? 'U', 0, 1));

mysqli_stmt_close($tstmt);
mysqli_close($dbc);

?>

<!-- PROFILE HEADER -->
<div class="profile-header">

	<div class="avatar-wrap">
		<div class="avatar"><?= $initials ?></div>
		<?php if($has_ff): ?>
		<div class="ff-badge"><i class="fa-solid fa-crown"></i> FF MEMBER</div>
		<?php endif; ?>
	</div>

	<div class="profile-info">
		<h2><?= htmlspecialchars($name ?? 'Customer') ?></h2>
		<div class="handle">
			<i class="fa-solid fa-fingerprint"></i>&nbsp;
			<?= $has_ff ? htmlspecialchars($ff_no) : 'Not enrolled in Frequent Flyer' ?>
		</div>
		<div class="profile-meta">
			<div class="meta-item">
				<i class="fa-solid fa-envelope"></i>
				<?= htmlspecialchars($email ?? '—') ?>
			</div>
			<div class="meta-item">
				<i class="fa-solid fa-phone"></i>
				<?= htmlspecialchars($phone ?? '—') ?>
			</div>
			<?php if(!empty($address)): ?>
			<div class="meta-item">
				<i class="fa-solid fa-location-dot"></i>
				<?= htmlspecialchars($address) ?>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<a href="edit_profile.php" class="edit-btn">
		<i class="fa-solid fa-pen-to-square"></i> Edit Profile
	</a>

</div>


<!-- TWO-COLUMN GRID -->
<div class="page-grid">

	<!-- LEFT: Personal Details -->
	<div class="glass-card">
		<div class="card-title">
			<i class="fa-solid fa-circle-user"></i>
			Personal Details
		</div>

		<div class="detail-row">
			<div class="detail-icon"><i class="fa-solid fa-user"></i></div>
			<div>
				<div class="detail-label">Full Name</div>
				<div class="detail-value"><?= htmlspecialchars($name ?? '—') ?></div>
			</div>
		</div>

		<div class="detail-row">
			<div class="detail-icon"><i class="fa-solid fa-id-card"></i></div>
			<div>
				<div class="detail-label">Customer ID</div>
				<div class="detail-value"><?= htmlspecialchars($_SESSION['userid']) ?></div>
			</div>
		</div>

		<div class="detail-row">
			<div class="detail-icon"><i class="fa-solid fa-envelope"></i></div>
			<div>
				<div class="detail-label">Email Address</div>
				<div class="detail-value"><?= htmlspecialchars($email ?? '—') ?></div>
			</div>
		</div>

		<div class="detail-row">
			<div class="detail-icon"><i class="fa-solid fa-phone"></i></div>
			<div>
				<div class="detail-label">Phone Number</div>
				<div class="detail-value"><?= htmlspecialchars($phone ?? '—') ?></div>
			</div>
		</div>

		<div class="detail-row">
			<div class="detail-icon"><i class="fa-solid fa-location-dot"></i></div>
			<div>
				<div class="detail-label">Address</div>
				<div class="detail-value"><?= htmlspecialchars($address ?? '—') ?></div>
			</div>
		</div>

	</div>

	<!-- RIGHT: FF Status + Preferences -->
	<div style="display:flex;flex-direction:column;gap:28px;">

		<!-- Frequent Flyer -->
		<div class="glass-card">
			<div class="card-title">
				<i class="fa-solid fa-star"></i>
				Frequent Flyer Status
			</div>

			<?php if($has_ff): ?>

			<div class="mileage-numbers">
				<span class="big"><?= number_format($mileage) ?></span>
				<span class="sub">mileage points</span>
			</div>
			<div class="progress-track">
				<div class="progress-fill" style="width:<?= $pct ?>%"></div>
			</div>
			<div class="next-tier">
				<span><?= number_format(max(0, $mileage_next - $mileage)) ?> pts</span>
				more to reach Platinum tier
			</div>

			<div class="mini-stats">
				<div class="mini-stat">
					<i class="fa-solid fa-hashtag"></i>
					<div class="val" style="font-size:13px;line-height:1.3;">
						<?= htmlspecialchars($ff_no) ?>
					</div>
					<div class="lbl">FF Number</div>
				</div>
				<div class="mini-stat">
					<i class="fa-solid fa-plane"></i>
					<div class="val"><?= $total_bookings ?></div>
					<div class="lbl">Total Bookings</div>
				</div>
			</div>

			<?php else: ?>
			<p style="color:#94a3b8;padding:8px 0 4px;">
				You are not yet enrolled in the Frequent Flyer programme.
			</p>
			<?php endif; ?>

		</div>

		<!-- Preferences -->
		<div class="glass-card">
			<div class="card-title">
				<i class="fa-solid fa-sliders"></i>
				Travel Preferences
			</div>

			<div class="pref-row">
				<div class="pref-label">
					<i class="fa-solid fa-bell"></i>
					Flight delay notifications
				</div>
				<div class="toggle"></div>
			</div>

			<div class="pref-row">
				<div class="pref-label">
					<i class="fa-solid fa-envelope-open-text"></i>
					Email booking confirmations
				</div>
				<div class="toggle"></div>
			</div>

			<div class="pref-row">
				<div class="pref-label">
					<i class="fa-solid fa-utensils"></i>
					Vegetarian meal preference
				</div>
				<div class="toggle off"></div>
			</div>

			<div class="pref-row">
				<div class="pref-label">
					<i class="fa-solid fa-wheelchair"></i>
					Accessibility assistance
				</div>
				<div class="toggle off"></div>
			</div>

		</div>

	</div>

</div>

<!-- RECENT BOOKINGS -->
<div class="glass-card" style="margin-bottom:30px;">

	<div class="card-title">
		<i class="fa-solid fa-clock-rotate-left"></i>
		Recent Bookings
	</div>

	<?php if($tickets_result && mysqli_num_rows($tickets_result) > 0): ?>
	<?php while($row = mysqli_fetch_assoc($tickets_result)):

		$bs  = strtolower($row['booking_status'] ?? '');
		$bsc = 'bs-default';
		if(str_contains($bs,'confirm'))  $bsc = 'bs-confirmed';
		elseif(str_contains($bs,'pend')) $bsc = 'bs-pending';
		elseif(str_contains($bs,'canc')) $bsc = 'bs-cancelled';

	?>
	<div class="ticket-row">

		<div class="ticket-icon">
			<i class="fa-solid fa-plane"></i>
		</div>

		<div class="ticket-info">
			<div class="ticket-top">
				<i class="fa-solid fa-ticket"></i>
				PNR: <?= htmlspecialchars($row['pnr']) ?>
				&nbsp;·&nbsp;
				Flight <?= htmlspecialchars($row['flight_no']) ?>
			</div>
			<div class="ticket-sub">
				<span>
					<i class="fa-regular fa-calendar"></i>
					<?= htmlspecialchars($row['journey_date']) ?>
				</span>
				<span>
					<i class="fa-solid fa-chair"></i>
					<?= htmlspecialchars($row['class']) ?>
				</span>
				<span>
					<i class="fa-solid fa-users"></i>
					<?= htmlspecialchars($row['no_of_passengers']) ?> pax
				</span>
			</div>
		</div>

		<div class="bstatus <?= $bsc ?>">
			<?= htmlspecialchars($row['booking_status']) ?>
		</div>

	</div>
	<?php endwhile; ?>

	<?php else: ?>
	<p style="color:#94a3b8;text-align:center;padding:24px 0;">No bookings found.</p>
	<?php endif; ?>

</div>

</body>
</html>
