<?php
	session_start();
?>
<html>
<head>
	<title>
		Enter Travel/Ticket Details
	</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
		rgba(5,10,25,0.94)),
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
		padding:18px 30px;

		display:flex;
		justify-content:space-between;
		align-items:center;

		margin-bottom:35px;
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

		padding:10px 18px;

		border-radius:12px;

		transition:0.3s;
	}

	.nav-links a:hover{

		background:linear-gradient(135deg,#2563eb,#1d4ed8);
	}

	/* FORM CONTAINER */

	.form-container{

		border-radius:28px;

		padding:40px;

		box-shadow:0 10px 30px rgba(0,0,0,0.35);
	}

	h2{
	font-family:'Cormorant Garamond',serif;
	font-size:42px;
	font-weight:700;
	color:white;
	margin-bottom:25px;
}

	p strong{

		font-size:18px;

		color:#dbeafe;
	}

	table{

		width:100%;

		border-collapse:collapse;

		margin-top:15px;
	}

	td{

		padding:12px;
		color:white;
	}

	/* INPUTS */

	input[type=text],
input[type=number],
select{

	width:100%;
	padding:14px 16px;
	border:none;
	border-radius:14px;
	background:rgba(255,255,255,0.12);
	color:white;
	font-size:15px;
	outline:none;
}

	select option{

		color:black;
	}

	input[type=radio]{

		margin-left:10px;
		margin-right:10px;
	}

	/* SUBMIT BUTTON */

	input[type=submit]{

		background:linear-gradient(135deg,#2563eb,#1d4ed8);

		color:white;

		border:none;

		padding:14px 38px;

		border-radius:14px;

		font-size:16px;

		cursor:pointer;

		display:block;

		margin:35px auto 0;

		transition:0.3s;
	}

	input[type=submit]:hover{

		transform:translateY(-3px);

		box-shadow:0 10px 20px rgba(37,99,235,0.4);
	}

	hr{

		border:0;

		height:1px;

		background:rgba(255,255,255,0.12);

		margin-top:20px;
	}

	/* RESPONSIVE */

	@media(max-width:900px){

		.navbar{

			flex-direction:column;
			gap:20px;
		}

		.nav-links{

			flex-wrap:wrap;
			justify-content:center;
		}

		table,
		tr,
		td{

			display:block;
			width:100%;
		}

		.form-container{

			padding:25px;
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


			<a href="homepage.php#quick-actions">
				<i class="fa fa-desktop"></i> Dashboard
			</a>

			<a href="homepage.php#features">
				<i class="fa fa-plane"></i> About Us
			</a>


		</div>

	</div>

	<!-- FORM SECTION -->

	<div class="form-container">

	<?php

		$no_of_pass=$_SESSION['no_of_pass'];
		$class=$_SESSION['class'];
		$count=$_SESSION['count'];
		$flight_no=$_POST['select_flight'];

		$_SESSION['flight_no']=$flight_no;

		echo "<h2>ADD PASSENGERS DETAILS</h2>";

		echo "<form action=\"add_ticket_details_form_handler.php\" method=\"post\">";

		while($count<=$no_of_pass)
		{
			echo "<p><strong>PASSENGER ".$count."</strong></p>";

			echo "<table cellpadding=\"0\">";

			echo "<tr>";
			echo "<td class=\"fix_table_short\">Passenger's Name</td>";
			echo "<td class=\"fix_table_short\">Passenger's Age</td>";
			echo "<td class=\"fix_table_short\">Passenger's Gender</td>";
			echo "<td class=\"fix_table_short\">Passenger's Inflight Meal</td>";
			echo "<td class=\"fix_table_short\">Passenger's Frequent Flier ID (if applicable)</td>";
			echo "</tr>";

			echo "<tr>";

			echo "<td class=\"fix_table_short\"><input type=\"text\" name=\"pass_name[]\" required></td>";

			echo "<td class=\"fix_table_short\"><input type=\"number\" name=\"pass_age[]\" required></td>";

			echo "<td class=\"fix_table_short\">";
			echo "<select name=\"pass_gender[]\">";
			echo "<option value=\"male\">Male</option>";
			echo "<option value=\"female\">Female</option>";
			echo "<option value=\"other\">Other</option>";
			echo "</select>";
			echo "</td>";

			echo "<td class=\"fix_table_short\">";
			echo "<select name=\"pass_meal[]\">";
			echo "<option value=\"yes\">Yes</option>";
			echo "<option value=\"no\">No</option>";
			echo "</select>";
			echo "</td>";

			echo "<td class=\"fix_table_short\"><input type=\"text\" name=\"pass_ff_id[]\"></td>";

			echo "</tr>";

			echo "</table>";

			echo "<br><hr>";

			$count=$count+1;
		}

		echo "<br><h2>ENTER TRAVEL DETAILS</h2>";

		echo "<table cellpadding=\"5\">";

		echo "<tr>";
		echo "<td class=\"fix_table_short\">Do you want access to our Premium Lounge?</td>";
		echo "<td class=\"fix_table_short\">Do you want to opt for Priority Checkin?</td>";
		echo "<td class=\"fix_table_short\">Do you want to purchase Travel Insurance?</td>";
		echo "</tr>";

		echo "<tr>";

		echo "<td class=\"fix_table\">";
		echo "Yes <input type='radio' name='lounge_access' value='yes' checked/> 
			  No <input type='radio' name='lounge_access' value='no'/>";
		echo "</td>";

		echo "<td class=\"fix_table\">";
		echo "Yes <input type='radio' name='priority_checkin' value='yes' checked/> 
			  No <input type='radio' name='priority_checkin' value='no'/>";
		echo "</td>";

		echo "<td class=\"fix_table\">";
		echo "Yes <input type='radio' name='insurance' value='yes' checked/> 
			  No <input type='radio' name='insurance' value='no'/>";
		echo "</td>";

		echo "</tr>";

		echo "</table>";

		echo "<input type=\"submit\" value=\"Submit Travel/Ticket Details\" name=\"Submit\">";

		echo "</form>";

	?>

	</div>

</body>
</html>