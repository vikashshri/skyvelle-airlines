<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Delete Flight Handler</title>
</head>

<body>

<?php

	if(isset($_POST['Delete']))
	{
		$data_missing=array();

		/* FLIGHT NUMBER */

		if(empty($_POST['flight_no']))
		{
			$data_missing[]='Flight No.';
		}
		else
		{
			$flight_no=trim($_POST['flight_no']);
		}

		/* DEPARTURE DATE */

		if(empty($_POST['departure_date']))
		{
			$data_missing[]='Departure Date';
		}
		else
		{
			$departure_date=trim($_POST['departure_date']);
		}

		/* CHECK EMPTY FIELDS */

		if(empty($data_missing))
		{
			require_once('Database Connection file/mysqli_connect.php');

			$query="DELETE FROM Flight_Details
					WHERE flight_no=?
					AND departure_date=?";

			$stmt=mysqli_prepare($dbc,$query);

			if($stmt)
			{
				mysqli_stmt_bind_param(
					$stmt,
					"ss",
					$flight_no,
					$departure_date
				);

				if(mysqli_stmt_execute($stmt))
				{
					$affected_rows=mysqli_stmt_affected_rows($stmt);

					if($affected_rows==1)
					{
						mysqli_stmt_close($stmt);
						mysqli_close($dbc);

						header("location: delete_flight_details.php?msg=success");
						exit();
					}
					else
					{
						header("location: delete_flight_details.php?msg=failed");
						exit();
					}
				}
				else
				{
					echo "<h3>Execution Error:</h3>";
					echo mysqli_stmt_error($stmt);
				}

				mysqli_stmt_close($stmt);
			}
			else
			{
				echo "<h3>Query Preparation Failed:</h3>";
				echo mysqli_error($dbc);
			}

			mysqli_close($dbc);
		}
		else
		{
			echo "<h3>The following fields are missing:</h3>";

			foreach($data_missing as $missing)
			{
				echo $missing."<br>";
			}
		}
	}
	else
	{
		echo "Delete request not received";
	}

?>

</body>
</html>