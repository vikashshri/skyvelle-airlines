<?php

session_start();

require_once('Database Connection file/mysqli_connect.php');

if(isset($_POST['Submit']))
{

    $user_id = $_POST['user_id'];

    $password = $_POST['password'];

    $query = "SELECT customer_id,password
              FROM Customer
              WHERE customer_id=?";

    $stmt = mysqli_prepare($dbc,$query);

    mysqli_stmt_bind_param($stmt,"s",$user_id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    $count = mysqli_stmt_num_rows($stmt);

    if($count == 1)
    {

        mysqli_stmt_bind_result(
            $stmt,
            $customer_id,
            $db_password
        );

        mysqli_stmt_fetch($stmt);

        if($password == $db_password)
        {

            /* IMPORTANT SESSION */

            $_SESSION['login_user'] = $customer_id;

            header("location: customer_homepage.php");
        }

        else
        {
            echo "Invalid Password";
        }
    }

    else
    {
        echo "Customer ID not found";
    }

    mysqli_stmt_close($stmt);

    mysqli_close($dbc);
}

else
{
    echo "Login request not received";
}

?>