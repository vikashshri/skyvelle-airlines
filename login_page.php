<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "airline_reservation");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userid = $_POST['userid'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM customer
            WHERE customer_id='$userid'
            AND pwd='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['userid'] = $userid;

        header("Location:homepage.php");
        exit();

    } else {
        $error = "Invalid User ID or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Skyvelle Passenger Login</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

:root{
    --gold:#f4b183;
    --gold2:#ffd2b0;
    --ff-disp:'Cormorant Garamond', serif;
    --ff-body:'DM Sans', sans-serif;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    min-height:100vh;

    background:
    linear-gradient(rgba(0,0,0,0.60),
    rgba(0,0,0,0.60)),
    url('https://i.pinimg.com/1200x/50/ab/c2/50abc27dac64985f2f3935ddc74f09b6.jpg');

    background-size:cover;
    background-position:center;

    display:flex;
    justify-content:center;
    align-items:center;

    font-family:var(--ff-body);
}

.login-card{

    width:430px;

    background:rgba(255,255,255,0.10);
    backdrop-filter:blur(20px);

    border:1px solid rgba(255,255,255,0.15);

    border-radius:28px;

    padding:45px;

    color:white;

    box-shadow:0 15px 40px rgba(0,0,0,0.35);
}

.logo{
    text-align:center;
    font-family:var(--ff-disp);
    font-size:52px;
    font-weight:600;
    color:var(--gold);
    margin-bottom:10px;
}

.subtitle{
    text-align:center;
    color:#eee;
    margin-bottom:35px;

    font-size:13px;
    letter-spacing:2px;
    text-transform:uppercase;
}

.error{
    background:rgba(255,0,0,0.15);
    color:#ffd1d1;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    text-align:center;
    font-size:14px;
}

.input-box{
    margin-bottom:22px;
}

.input-box label{
    display:block;

    margin-bottom:8px;

    color:var(--gold);

    font-size:12px;
    letter-spacing:2px;
    text-transform:uppercase;
}

.input-box input{
    width:100%;

    padding:15px;

    border:none;
    outline:none;

    border-radius:12px;

    background:rgba(255,255,255,0.92);

    font-size:15px;
}

.login-btn{

    width:100%;

    padding:15px;

    border:none;
    border-radius:12px;

    background:var(--gold);

    color:#222;

    font-size:15px;
    font-weight:600;

    cursor:pointer;

    transition:0.3s;
}

.login-btn:hover{
    background:var(--gold2);
    transform:translateY(-2px);
}

.bottom-links{

    text-align:center;

    margin-top:25px;

    font-size:14px;
}

.bottom-links a{
    color:var(--gold2);
    text-decoration:none;
}

.bottom-links a:hover{
    text-decoration:underline;
}

.back-home{
    display:block;
    text-align:center;
    margin-top:15px;
}

.back-home a{
    color:#ddd;
    text-decoration:none;
    font-size:13px;
}

.back-home a:hover{
    color:white;
}

</style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        Skyvelle
    </div>

    <div class="subtitle">
        Passenger Login Portal
    </div>

    <?php
    if(isset($error)){
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST">

        <div class="input-box">
            <label>User ID</label>
            <input type="text"
                   name="userid"
                   placeholder="Enter your User ID"
                   required>
        </div>

        <div class="input-box">
            <label>Password</label>
            <input type="password"
                   name="password"
                   placeholder="Enter your Password"
                   required>
        </div>

        <button type="submit" class="login-btn">
            Login
        </button>

    </form>

    <div class="bottom-links">
        New User?
        <a href="new_user.php">Create Account</a>
    </div>

    <div class="back-home">
        <a href="index.php">← Back to Home</a>
    </div>

</div>

</body>
</html>