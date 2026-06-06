<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Booked Tickets | Skyvelle Airlines</title>

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

/* MAIN CONTENT */

.container{
	width:100%;
	padding:50px 30px;
	display:flex;
	justify-content:center;
}

.content-box{
	width:100%;
	max-width:1200px;
	padding:40px;
	border-radius:25px;
	box-shadow:0 8px 30px rgba(0,0,0,0.35);
	overflow-x:auto;
}

.content-box h2{
	text-align:center;
	margin-bottom:35px;
	font-size:30px;
	font-weight:600;
	color:white;
}

/* TABLE */

table{
	width:100%;
	border-collapse:collapse;
	margin-top:15px;
	overflow:hidden;
	border-radius:15px;
}

table th{
	background:#8d2747;
	color:white;
	padding:16px;
	font-size:15px;
	text-align:center;
}

table td{
	padding:15px;
	text-align:center;
	background:rgba(255,255,255,0.08);
	border-bottom:1px solid rgba(255,255,255,0.08);
	color:#f5f5f5;
	font-size:14px;
}

table tr:hover td{
	background:rgba(255,255,255,0.14);
	transition:0.3s;
}

/* MESSAGE BOX */

.message{
	text-align:center;
	font-size:18px;
	padding:20px;
	border-radius:15px;
	background:rgba(255,255,255,0.08);
	margin-top:20px;
}

.error{
	color:#ffb3c7;
}

.success{
	color:#d9ffd9;
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

	.content-box{
		padding:25px 15px;
	}

	#title{
		font-size:22px;
	}

	table th,
	table td{
		font-size:12px;
		padding:10px;
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
			<a href="logout_handler.php">
				<i class="fa fa-sign-out-alt"></i> Logout
			</a>
		</li>

	</ul>

</div>

<!-- CONTENT -->

<div class="container">

	<div class="content-box">

		<h2>Booked Ticket Details</h2>

		<?php

			if(isset($_POST['Submit']))
			{
				$data_missing=array();

				if(empty($_POST['flight_no']))
				{
					$data_missing[]='Flight Number';
				}
				else
				{
					$flight_no=trim($_POST['flight_no']);
				}

				if(empty($_POST['departure_date']))
				{
					$data_missing[]='Departure Date';
				}
				else
				{
					$departure_date=$_POST['departure_date'];
				}

				if(empty($data_missing))
				{
					require_once('Database Connection file/mysqli_connect.php');

					$query="SELECT pnr,
							date_of_reservation,
							class,
							no_of_passengers,
							payment_id,
							customer_id
							FROM Ticket_Details
							WHERE flight_no=?
							AND journey_date=?
							AND booking_status='CONFIRMED'
							ORDER BY class";

					$stmt=mysqli_prepare($dbc,$query);

					mysqli_stmt_bind_param($stmt,"ss",$flight_no,$departure_date);

					mysqli_stmt_execute($stmt);

					mysqli_stmt_bind_result(
						$stmt,
						$pnr,
						$date_of_reservation,
						$class,
						$no_of_passengers,
						$payment_id,
						$customer_id
					);

					mysqli_stmt_store_result($stmt);

					if(mysqli_stmt_num_rows($stmt)==0)
					{
						echo "<div class='message error'>
								No booked tickets found for this flight.
							  </div>";
					}
					else
					{
						echo "<table>";

						echo "<tr>
								<th>PNR</th>
								<th>Date of Reservation</th>
								<th>Class</th>
								<th>No. of Passengers</th>
								<th>Payment ID</th>
								<th>Customer ID</th>
							  </tr>";

						while(mysqli_stmt_fetch($stmt))
						{
        					echo "<tr>

									<td>".$pnr."</td>
									<td>".$date_of_reservation."</td>
									<td>".$class."</td>
									<td>".$no_of_passengers."</td>
									<td>".$payment_id."</td>
									<td>".$customer_id."</td>

        						  </tr>";
    					}

    					echo "</table>";
    				}

					mysqli_stmt_close($stmt);
					mysqli_close($dbc);
				}
				else
				{
					echo "<div class='message error'>
							The following fields are missing:<br><br>";

					foreach($data_missing as $missing)
					{
						echo "• ".$missing."<br>";
					}

					echo "</div>";
				}
			}
			else
			{
				echo "<div class='message error'>
						Submit request not received.
					  </div>";
			}

		?>

	</div>

</div>

</body>
</html>