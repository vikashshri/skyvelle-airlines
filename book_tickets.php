<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Search Flights | Skyvelle Airlines</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
	--ff-heading:'Playfair Display',serif;
	--ff-body:'Poppins',sans-serif;

	--gold:#c9a84c;
	--gold2:#e8c97a;
	--ink:#060d1f;
}
*{
	margin:0;
	padding:0;
	box-sizing:border-box;
	font-family:'Poppins',sans-serif;
}

body{

	background:
	linear-gradient(rgba(10,10,15,0.88),
	rgba(10,10,15,0.90)),
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
	font-family:var(--ff-heading);
	font-size:36px;
	font-weight:600;
	letter-spacing:1px;
	color:white;
}

.nav-links{

	display:flex;
	gap:15px;
}

.nav-links a{

	text-decoration:none;
	color:white;

	padding:12px 18px;

	border-radius:12px;

	transition:0.3s;
}

.nav-links a:hover{

	background:rgba(255,255,255,0.15);
}

/* FORM CONTAINER */

.form-container{

	width:100%;
	max-width:850px;

	margin:auto;

	border:1px solid rgba(255,255,255,0.08);

	border-radius:30px;

	padding:45px;

	box-shadow:0 10px 30px rgba(0,0,0,0.35);
}

/* TITLE */

.form-title{

	text-align:center;

	margin-bottom:35px;
}

.form-title h2{

	font-size:38px;

	font-family:'Playfair Display',serif;

	margin-bottom:10px;
}

.form-title p{

	color:#d1d5db;
}

/* FORM GRID */

.form-grid{

	display:grid;

	grid-template-columns:repeat(auto-fit,minmax(280px,1fr));

	gap:25px;

	margin-bottom:25px;
}

/* INPUT GROUP */

.input-group{

	display:flex;
	flex-direction:column;
}

.input-group label{

	margin-bottom:10px;

	font-size:15px;

	color:#f3f4f6;
}

.input-group input,
.input-group select{

	padding:14px 16px;

	border:none;

	border-radius:14px;

	background:rgba(255,255,255,0.12);

	color:white;

	font-size:15px;

	outline:none;
}

.input-group input::placeholder{

	color:#e5e7eb;
}

/* DATE ICON */

input[type="date"]{

	color:white;
}

/* SELECT OPTION */

select option{

	color:black;
}

/* BUTTON */

.search-btn{

	width:100%;

	padding:16px;

	border:none;

	border-radius:16px;

	background:linear-gradient(135deg,#2563eb,#1e40af);

	color:white;

	font-size:17px;

	font-weight:600;

	cursor:pointer;

	transition:0.3s;
}

.search-btn:hover{

	transform:translateY(-3px);

	box-shadow:0 10px 20px rgba(37,99,235,0.35);
}
/* RESPONSIVE */

@media(max-width:768px){

	.navbar{

		flex-direction:column;
		gap:20px;
	}

	.nav-links{

		flex-wrap:wrap;
		justify-content:center;
	}

	.form-container{

		padding:30px 20px;
	}

	.form-title h2{

		font-size:28px;
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

	<div class="nav-links">

		<a href="homepage.php">
			 Home
		</a>

		<a href="homepage.php#quick-actions">
			 Dashboard
		</a>

		<a href="logout_handler.php">
			Logout
		</a>

	</div>

</div>

<!-- SEARCH FORM -->

<div class="form-container">

	<div class="form-title">

		<h2>Search Available Flights</h2>

		<p>
			Find your perfect journey with Skyvelle Airlines
		</p>

	</div>

	<form action="view_flights_form_handler.php" method="post">

		<div class="form-grid">

			<!-- ORIGIN -->

			<div class="input-group">

				<label>Enter the Origin</label>

				<input list="origins" name="origin" placeholder="From" required>

				<datalist id="origins">

					<option value="bangalore">

				</datalist>

			</div>

			<!-- DESTINATION -->

			<div class="input-group">

				<label>Enter the Destination</label>

				<input list="destinations" name="destination" placeholder="To" required>

				<datalist id="destinations">

					<option value="mumbai">
					<option value="mysore">
					<option value="mangalore">
					<option value="chennai">
					<option value="hyderabad">

				</datalist>

			</div>

			<!-- DATE -->

			<div class="input-group">

				<label>Enter the Departure Date</label>

				<input type="date" name="dep_date" min=
				<?php 
					$todays_date=date('Y-m-d'); 
					echo $todays_date;
				?> 
				max=
				<?php 
					$max_date=date_create(date('Y-m-d'));
					date_add($max_date,date_interval_create_from_date_string("90 days")); 
					echo date_format($max_date,"Y-m-d");
				?> required>

			</div>

			<!-- PASSENGERS -->

			<div class="input-group">

				<label>Enter the No. of Passengers</label>

				<input type="number" name="no_of_pass" placeholder="Eg. 5" required>

			</div>

			<!-- CLASS -->

			<div class="input-group">

				<label>Enter the Class</label>

				<select name="class">

					<option value="economy">Economy</option>
					<option value="business">Business</option>

				</select>

			</div>

		</div>

		<button type="submit" class="search-btn" name="Search">

			<i class="fa-solid fa-magnifying-glass"></i>
			Search for Available Flights

		</button>

	</form>

</div>

</body>
</html>