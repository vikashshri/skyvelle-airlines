<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Delete Flight Schedule | Skyvelle Airlines</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

*{
	margin:0;
	padding:0;
	box-sizing:border-box;
	font-family:'Poppins',sans-serif;
}

body{
	min-height:100vh;
	background:
	linear-gradient(rgba(60,0,15,0.78), rgba(60,0,15,0.78)),
	url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
	background-size:cover;
	background-position:center;
	background-attachment:fixed;
	color:white;
}

/* NAVBAR */

.navbar{
	width:100%;
	padding:18px 60px;
	display:flex;
	justify-content:space-between;
	align-items:center;
}

.logo-section{
	display:flex;
	align-items:center;
	gap:15px;
}

#title{
	font-size:28px;
	font-weight:600;
	letter-spacing:1px;
	color:#fff;
}

.nav-links{
	list-style:none;
	display:flex;
	gap:20px;
}

.nav-links li a{
	text-decoration:none;
	color:white;
	font-size:15px;
	padding:10px 18px;
	border-radius:10px;
	transition:0.3s ease;
	
}

.nav-links li a:hover{
	background:#a63d5d;
	transform:translateY(-2px);
}

/* FORM SECTION */

.container{
	width:100%;
	display:flex;
	justify-content:center;
	align-items:center;
	padding:80px 20px;
}

.form-box{
	width:100%;
	max-width:700px;
	
	padding:45px;
	border-radius:25px;
	box-shadow:0 8px 30px rgba(0,0,0,0.35);

}

.form-box h2{
	text-align:center;
	margin-bottom:35px;
	font-size:28px;
	font-weight:600;
	color:white;
}

.message{
	padding:14px;
	border-radius:12px;
	margin-bottom:25px;
	text-align:center;
	font-weight:500;
}

.success{
	background:rgba(0,255,120,0.12);
	color:#9cffc7;
	border:1px solid rgba(0,255,120,0.2);
}

.error{
	background:rgba(255,0,80,0.12);
	color:#ffb3c7;
	border:1px solid rgba(255,0,80,0.2);
}

/* FORM */

.form-grid{
	display:grid;
	grid-template-columns:1fr 1fr;
	gap:25px;
}

.input-group label{
	display:block;
	margin-bottom:10px;
	font-size:15px;
	font-weight:500;
	color:#f2d6df;
}

.input-group input{
	width:100%;
	padding:14px 16px;
	border:none;
	outline:none;
	border-radius:12px;
	background:rgba(255,255,255,0.15);
	color:white;
	font-size:15px;
	transition:0.3s;
}

.input-group input:focus{
	background:rgba(255,255,255,0.22);
	border:1px solid #d98aa2;
}

input[type="date"]::-webkit-calendar-picker-indicator{
	filter:invert(1);
	cursor:pointer;
}

/* BUTTON */

.btn-box{
	text-align:center;
	margin-top:35px;
}

.submit-btn{
	padding:14px 50px;
	border:none;
	border-radius:14px;
	background:linear-gradient(135deg,#a63d5d,#7d1f3c);
	color:white;
	font-size:16px;
	font-weight:600;
	cursor:pointer;
	transition:0.3s ease;
	box-shadow:0 4px 15px rgba(0,0,0,0.25);
}

.submit-btn:hover{
	transform:translateY(-3px);
	background:linear-gradient(135deg,#b94d6d,#8d2747);
}

/* RESPONSIVE */

@media(max-width:768px){

	.navbar{
		flex-direction:column;
		gap:20px;
		padding:20px;
	}

	.nav-links{
		flex-wrap:wrap;
		justify-content:center;
	}

	.form-grid{
		grid-template-columns:1fr;
	}

	.form-box{
		padding:30px 20px;
	}

	#title{
		font-size:22px;
	}
}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

	<div class="logo-section">
		<h1 id="title">✈ Skyvelle AIRLINES</h1>
	</div>

	<ul class="nav-links">

		

		<li>
			<a href="admin_homepage.php">
				<i class="fa fa-desktop"></i> Dashboard
			</a>
		</li>

		<li>
			<a href="logout_handler.php">
				<i class="fa fa-sign-out-alt"></i> Logout
			</a>
		</li>

	</ul>

</div>

<!-- FORM -->

<div class="container">

	<div class="form-box">

		<h2>Delete Flight Schedule</h2>

		<?php

			if(isset($_GET['msg']) && $_GET['msg']=='success')
			{
				echo "<div class='message success'>
						The flight schedule has been successfully deleted.
					  </div>";
			}
			else if(isset($_GET['msg']) && $_GET['msg']=='failed')
			{
				echo "<div class='message error'>
						Invalid Flight Number or Departure Date.
					  </div>";
			}

		?>

		<form action="delete_flight_details_form_handler.php" method="post">

			<div class="form-grid">

				<div class="input-group">
					<label>Flight Number</label>
					<input type="text"
						   name="flight_no"
						   placeholder="Eg: SV101"
						   required>
				</div>

				<div class="input-group">
					<label>Departure Date</label>
					<input type="date"
						   name="departure_date"
						   required>
				</div>

			</div>

			<div class="btn-box">

				<input type="submit"
					   value="Delete Flight"
					   name="Delete"
					   class="submit-btn">

			</div>

		</form>

	</div>

</div>

</body>
</html>