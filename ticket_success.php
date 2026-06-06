<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Booking Successful | Skyvelle Airlines</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">

<style>

*{
	margin:0;
	padding:0;
	box-sizing:border-box;
	font-family:'Poppins',sans-serif;
}

body{

	background:
	linear-gradient(rgba(5,10,25,0.92),
	rgba(5,10,25,0.95)),
	url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');

	background-size:cover;
	background-position:center;
	background-attachment:fixed;

	min-height:100vh;
	padding:30px;

	color:white;
}

/* NAVBAR */

.navbar{

	padding:18px 30px;

	display:flex;
	justify-content:space-between;
	align-items:center;

	margin-bottom:40px;
}

.logo-section{

	display:flex;
	align-items:center;
	gap:12px;
}

.logo-section h1{

	font-family:'Cormorant Garamond',serif;
	font-size:52px;
	font-weight:700;
	color:white;
}

.nav-links{

	display:flex;
	gap:15px;
	flex-wrap:wrap;
}

.nav-links a{

	text-decoration:none;
	color:white;

	padding:12px 18px;

	border-radius:12px;

	transition:0.3s;
}

.nav-links a:hover{

	background:linear-gradient(135deg,#2563eb,#1d4ed8);
}

/* SUCCESS CARD */

.success-card{

	max-width:850px;

	margin:auto;


	padding:60px 50px;

	text-align:center;

	box-shadow:0 10px 30px rgba(0,0,0,0.35);
}

.success-icon{

	width:120px;
	height:120px;

	margin:auto auto 25px;

	border-radius:50%;

	display:flex;
	align-items:center;
	justify-content:center;

	background:linear-gradient(135deg,#22c55e,#16a34a);

	font-size:60px;

	box-shadow:0 10px 25px rgba(34,197,94,0.35);
}

.success-title{

	font-family:'Cormorant Garamond',serif;

	font-size:54px;

	margin-bottom:15px;
}

.success-text{

	font-size:18px;

	line-height:1.9;

	color:#e5e7eb;

	margin-bottom:35px;
}

/* PNR BOX */

.ticket-box{

	background:rgba(255,255,255,0.08);

	border:1px solid rgba(255,255,255,0.08);

	border-radius:20px;

	padding:25px;

	margin:30px 0;
}

.ticket-box h3{

	font-size:18px;

	color:#93c5fd;

	margin-bottom:10px;
}

.pnr{

	font-size:34px;

	font-weight:700;

	letter-spacing:3px;

	color:#ffffff;
}

.amount{

	font-size:28px;

	font-weight:700;

	color:#4ade80;
}

/* BUTTONS */

.btn-group{

	display:flex;
	justify-content:center;
	gap:20px;
	flex-wrap:wrap;

	margin-top:35px;
}

.btn{

	text-decoration:none;

	padding:14px 30px;

	border-radius:14px;

	font-size:16px;

	font-weight:600;

	transition:0.3s;
}

.primary-btn{

	background:linear-gradient(135deg,#2563eb,#1d4ed8);

	color:white;
}

.secondary-btn{

	background:rgba(255,255,255,0.1);

	color:white;

	border:1px solid rgba(255,255,255,0.08);
}

.btn:hover{

	transform:translateY(-3px);
}

/* RESPONSIVE */

@media(max-width:768px){

	.navbar{

		flex-direction:column;
		gap:20px;
	}

	.logo-section h1{

		font-size:40px;
	}

	.success-card{

		padding:40px 25px;
	}

	.success-title{

		font-size:40px;
	}
}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

	<div class="logo-section">

		<h1>✈ Skyvelle Airlines</h1>

	</div>

</div>

<!-- SUCCESS SECTION -->

<div class="success-card">

	<div class="success-icon">
		<i class="fa-solid fa-check"></i>
	</div>

	<h2 class="success-title">
		Booking Successful
	</h2>

	<p class="success-text">
		Your payment has been successfully processed and your flight reservation is now confirmed.
		Thank you for choosing <strong>Skyvelle Airlines</strong>.
	</p>

	<div class="ticket-box">

		<h3>Total Amount Paid</h3>

		<div class="amount">
			₹ <?php echo $_SESSION['total_amount']; ?>
		</div>

	</div>

	<div class="ticket-box">

		<h3>Your PNR Number</h3>

		<div class="pnr">
			<?php echo $_SESSION['pnr']; ?>
		</div>

	</div>

	<div class="btn-group">

		<a href="view_booked_tickets.php" class="btn primary-btn">
			<i class="fa-solid fa-ticket"></i>
			View My Tickets
		</a>			
		<a href="homepage.php#quick-actions" class="btn secondary-btn">
			<i class="fa-solid fa-house"></i>
			Back to Dashboard
		</a>

	</div>

</div>

</body>
</html>