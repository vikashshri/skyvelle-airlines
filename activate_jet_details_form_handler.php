<?php
session_start();

if(isset($_POST['Activate']))
{
	if(empty($_POST['jet_id']))
	{
		header("location: activate_jet_details.php?msg=failed");
		exit();
	}

	$jet_id=trim($_POST['jet_id']);

	require_once('Database Connection file/mysqli_connect.php');

	$query="UPDATE Jet_Details
			SET active='Yes'
			WHERE jet_id=?
			AND active='No'";

	$stmt=mysqli_prepare($dbc,$query);

	if($stmt)
	{
		mysqli_stmt_bind_param($stmt,"s",$jet_id);

		if(mysqli_stmt_execute($stmt))
		{
			$affected_rows=mysqli_stmt_affected_rows($stmt);

			if($affected_rows==1)
			{
				mysqli_stmt_close($stmt);
				mysqli_close($dbc);

				header("location: activate_jet_details.php?msg=success");
				exit();
			}
			else
			{
				mysqli_stmt_close($stmt);
				mysqli_close($dbc);

				header("location: activate_jet_details.php?msg=failed");
				exit();
			}
		}
		else
		{
			echo mysqli_stmt_error($stmt);
		}
	}
	else
	{
		echo mysqli_error($dbc);
	}
}
else
{
	header("location: activate_jet_details.php");
	exit();
}
?>