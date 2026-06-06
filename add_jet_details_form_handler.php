<?php
	session_start();

	if(isset($_POST['Submit']))
	{
		$data_missing=array();

		if(empty($_POST['jet_id']))
		{
			$data_missing[]='Jet ID';
		}
		else
		{
			$jet_id=trim($_POST['jet_id']);
		}

		if(empty($_POST['jet_type']))
		{
			$data_missing[]='Jet Type';
		}
		else
		{
			$jet_type=trim($_POST['jet_type']);
		}

		if(empty($_POST['jet_capacity']))
		{
			$data_missing[]='Jet Capacity';
		}
		else
		{
			$jet_capacity=$_POST['jet_capacity'];
		}

		if(empty($data_missing))
		{
			require_once('Database Connection file/mysqli_connect.php');

			$query="INSERT INTO Jet_Details
					(jet_id,jet_type,total_capacity,active)
					VALUES
					(?,?,?,'Yes')";

			$stmt=mysqli_prepare($dbc,$query);

			mysqli_stmt_bind_param(
				$stmt,
				"ssi",
				$jet_id,
				$jet_type,
				$jet_capacity
			);

			mysqli_stmt_execute($stmt);

			$affected_rows=mysqli_stmt_affected_rows($stmt);

			mysqli_stmt_close($stmt);
			mysqli_close($dbc);

			if($affected_rows==1)
			{
				header("location:add_jet_details.php?msg=success");
			}
			else
			{
				header("location:add_jet_details.php?msg=failed");
			}
		}
		else
		{
			echo "
			<!DOCTYPE html>
			<html>
			<head>

			<link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap'
			rel='stylesheet'>

			<style>

			body{
				font-family:'Poppins',sans-serif;
				background:#3d0015;
				color:white;
				display:flex;
				justify-content:center;
				align-items:center;
				height:100vh;
				margin:0;
			}

			.error-box{
				background:rgba(255,255,255,0.08);
				padding:40px;
				border-radius:20px;
				text-align:center;
				width:400px;
			}

			h2{
				color:#ffb3c7;
			}

			</style>

			</head>

			<body>

			<div class='error-box'>

				<h2>The following fields are missing:</h2>";

				foreach($data_missing as $missing)
				{
					echo $missing."<br>";
				}

			echo "</div>

			</body>
			</html>";
		}
	}
	else
	{
		echo "Submit request not received";
	}
?>