<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Deactivate Aircraft Handler</title>

</head>

<body>

<?php

	if(isset($_POST['Deactivate']))
	{
		$data_missing=array();

		/* GET JET ID */

		if(empty($_POST['jet_id']))
		{
			$data_missing[]='Jet ID';
		}
		else
		{
			$jet_id=trim($_POST['jet_id']);
		}

		/* CHECK IF ANY FIELD IS EMPTY */

		if(empty($data_missing))
		{
			require_once('Database Connection file/mysqli_connect.php');

			/* UPDATE QUERY */

			$query="UPDATE Jet_Details
					SET active='No'
					WHERE jet_id=?
					AND active='Yes'";

			$stmt=mysqli_prepare($dbc,$query);

			/* CHECK QUERY PREPARATION */

			if($stmt)
			{
				/* BIND PARAMETERS */

				mysqli_stmt_bind_param($stmt,"s",$jet_id);

				/* EXECUTE QUERY */

				if(mysqli_stmt_execute($stmt))
				{
					$affected_rows=mysqli_stmt_affected_rows($stmt);

					/* SUCCESS */

					if($affected_rows==1)
					{
						mysqli_stmt_close($stmt);
						mysqli_close($dbc);

						header("location: deactivate_jet_details.php?msg=success");
						exit();
					}
					else
					{
						echo "<h3>Aircraft could not be deactivated.</h3>";

						echo "Possible reasons:<br><br>";

						echo "1. Invalid Jet ID entered.<br>";
						echo "2. Aircraft is already deactivated.<br>";
						echo "3. Jet ID does not exist in database.<br>";
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
		echo "Deactivate request not received";
	}

?>

</body>

</html>