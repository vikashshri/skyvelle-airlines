<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Available Flights | Skyvelle Airlines</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
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
	gap:15px;
}

.logo-section i{

	font-size:34px;
	color:#60a5fa;
}

.logo-section h1{

	font-family:'Playfair Display',serif;
	font-size:39px;
	font-weight:700;
	color:white;
	letter-spacing:1px;
}

.nav-links{

	display:flex;
	gap:15px;
	flex-wrap:wrap;
}

.nav-links a{

	font-family:'Poppins',sans-serif;
	font-size:15px;
	font-weight:500;
	text-decoration:none;
	color:white;
	padding:12px 18px;
	border-radius:12px;
	transition:0.3s;
}

.nav-links a:hover{

	background:linear-gradient(135deg,#2563eb,#1d4ed8);
}

/* MAIN CONTAINER */

.main-container{


	padding:40px;

	box-shadow:0 10px 30px rgba(0,0,0,0.35);
}

.page-title{

	font-family:'Playfair Display',serif;
	font-size:48px;
	font-weight:700;
	margin-bottom:35px;
	text-align:center;
	color:white;
	letter-spacing:1px;
}

/* TABLE */

table{

	width:100%;

	border-collapse:collapse;

	margin-top:20px;

	overflow:hidden;

	border-radius:18px;
}

th{

	background:linear-gradient(135deg,#2563eb,#1d4ed8);
	padding:16px;
	color:white;
	font-size:15px;
	font-weight:600;
	letter-spacing:0.5px;
}

td{

	padding:16px;

	text-align:center;

	background:rgba(255,255,255,0.06);

	border-bottom:1px solid rgba(255,255,255,0.08);

	color:#f1f5f9;
}

tr:hover td{

	background:rgba(37,99,235,0.15);
}

/* RADIO BUTTON */

input[type=radio]{

	transform:scale(1.2);

	accent-color:#2563eb;
}

/* BUTTON */

input[type=submit]{

	background:linear-gradient(135deg,#2563eb,#1d4ed8);

	color:white;

	border:none;

	padding:14px 40px;

	border-radius:14px;

	font-size:16px;

	font-weight:600;

	cursor:pointer;

	margin-top:30px;

	display:block;

	margin-left:auto;
	margin-right:auto;

	transition:0.3s;
}

input[type=submit]:hover{

	transform:translateY(-3px);

	box-shadow:0 10px 20px rgba(37,99,235,0.35);
}

/* MESSAGE */

.message{

	text-align:center;

	font-size:22px;

	margin-top:20px;

	color:#f8fafc;
}

/* RESPONSIVE */

@media(max-width:1000px){

	table{

		font-size:13px;
	}

	th,td{

		padding:12px 8px;
	}
}

@media(max-width:768px){

	.navbar{

		flex-direction:column;
		gap:20px;
	}

	.page-title{

		font-size:32px;
	}

	.main-container{

		padding:25px 15px;
	}

	table{

		display:block;
		overflow-x:auto;
		white-space:nowrap;
	}
}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

	<div class="logo-section">

		<i class="fa-solid fa-plane"></i>

		<h1>Skyvelle Airlines</h1>

	</div>

	</div>

</div>

<!-- MAIN CONTENT -->

<div class="main-container">

<h2 class="page-title">Available Flights</h2>

<?php
	if(isset($_POST['Search']))
	{
		$data_missing=array();

		if(empty($_POST['origin']))
		{
			$data_missing[]='Origin';
		}
		else
		{
			$origin=$_POST['origin'];
		}

		if(empty($_POST['destination']))
		{
			$data_missing[]='Destination';
		}
		else
		{
			$destination=$_POST['destination'];
		}

		if(empty($_POST['dep_date']))
		{
			$data_missing[]='Departure Date';
		}
		else
		{
			$dep_date=trim($_POST['dep_date']);
		}

		if(empty($_POST['no_of_pass']))
		{
			$data_missing[]='No. of Passengers';
		}
		else
		{
			$no_of_pass=trim($_POST['no_of_pass']);
		}

		if(empty($_POST['class']))
		{
			$data_missing[]='Class';
		}
		else
		{
			$class=trim($_POST['class']);
		}

		if(empty($data_missing))
		{
			$_SESSION['no_of_pass']=$no_of_pass;
			$_SESSION['class']=$class;
			$count=1;
			$_SESSION['count']=$count;
			$_SESSION['journey_date']=$dep_date;

			require_once('Database Connection file/mysqli_connect.php');

			if($class=="economy")
			{
				$query="SELECT flight_no,from_city,to_city,departure_date,departure_time,arrival_date,arrival_time,price_economy FROM Flight_Details where from_city=? and to_city=? and departure_date=? and seats_economy>=? ORDER BY departure_time";

				$stmt=mysqli_prepare($dbc,$query);

				mysqli_stmt_bind_param($stmt,"sssi",$origin,$destination,$dep_date,$no_of_pass);

				mysqli_stmt_execute($stmt);

				mysqli_stmt_bind_result($stmt,$flight_no,$from_city,$to_city,$departure_date,$departure_time,$arrival_date,$arrival_time,$price_economy);

				mysqli_stmt_store_result($stmt);

				if(mysqli_stmt_num_rows($stmt)==0)
				{
					echo "<div class='message'>No flights are available!</div>";
				}
				else
				{
					echo "<form action='book_tickets2.php' method='post'>";

					echo "<table>";

					echo "<tr>
					<th>Flight No.</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>Departure Date</th>
					<th>Departure Time</th>
					<th>Arrival Date</th>
					<th>Arrival Time</th>
					<th>Price(Economy)</th>
					<th>Select</th>
					</tr>";

					while(mysqli_stmt_fetch($stmt))
					{
						echo "<tr>
						<td>".$flight_no."</td>
						<td>".$from_city."</td>
						<td>".$to_city."</td>
						<td>".$departure_date."</td>
						<td>".$departure_time."</td>
						<td>".$arrival_date."</td>
						<td>".$arrival_time."</td>
<td><strong>₹ ".number_format($price_economy)."</strong></td>						<td><input type='radio' name='select_flight' value='".$flight_no."'></td>
						</tr>";
					}

					echo "</table>";

					echo "<input type='submit' value='Select Flight' name='Select'>";

					echo "</form>";
				}
			}
			else if($class=="business")
			{
				$query="SELECT flight_no,from_city,to_city,departure_date,departure_time,arrival_date,arrival_time,price_business FROM Flight_Details where from_city=? and to_city=? and departure_date=? and seats_business>=? ORDER BY departure_time";

				$stmt=mysqli_prepare($dbc,$query);

				mysqli_stmt_bind_param($stmt,"sssi",$origin,$destination,$dep_date,$no_of_pass);

				mysqli_stmt_execute($stmt);

				mysqli_stmt_bind_result($stmt,$flight_no,$from_city,$to_city,$departure_date,$departure_time,$arrival_date,$arrival_time,$price_business);

				mysqli_stmt_store_result($stmt);

				if(mysqli_stmt_num_rows($stmt)==0)
				{
					echo "<div class='message'>No flights are available!</div>";
				}
				else
				{
					echo "<form action='book_tickets2.php' method='post'>";

					echo "<table>";

					echo "<tr>
					<th>Flight No.</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>Departure Date</th>
					<th>Departure Time</th>
					<th>Arrival Date</th>
					<th>Arrival Time</th>
					<th>Price(Business)</th>
					<th>Select</th>
					</tr>";

					while(mysqli_stmt_fetch($stmt))
					{
						echo "<tr>
						<td>".$flight_no."</td>
						<td>".$from_city."</td>
						<td>".$to_city."</td>
						<td>".$departure_date."</td>
						<td>".$departure_time."</td>
						<td>".$arrival_date."</td>
						<td>".$arrival_time."</td>
<td><strong>₹ ".number_format($price_business)."</strong></td>						<td><input type='radio' name='select_flight' value='".$flight_no."'></td>
						</tr>";
					}

					echo "</table>";

					echo "<input type='submit' value='Select Flight' name='Select'>";

					echo "</form>";
				}
			}

			mysqli_stmt_close($stmt);
			mysqli_close($dbc);
		}
		else
		{
			echo "<div class='message'>";

			echo "The following data fields were empty! <br><br>";

			foreach($data_missing as $missing)
			{
				echo $missing . "<br>";
			}

			echo "</div>";
		}
	}
	else
	{
		echo "<div class='message'>Search request not received</div>";
	}
?>

</div>

</body>
</html>