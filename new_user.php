<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "airline_reservation");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = $_POST['customer_id'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $address = $_POST['address'];

    $check = "SELECT * FROM customer WHERE customer_id='$customer_id'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {

        $error = "User ID already exists";

    } else {

        $sql = "INSERT INTO customer
                (customer_id,pwd,name,email,phone_no,address)
                VALUES
                ('$customer_id','$password','$name','$email','$phone_no','$address')";

        $result = mysqli_query($conn, $sql);

        if ($result) {

            header("Location: login_page.php");
            exit();

        } else {

            $error = "Registration Failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Create Skyvelle Account</title>

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

    padding:40px;

    font-family:var(--ff-body);
}

.register-card{

    width:500px;

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

.title{
    text-align:center;

    font-family:var(--ff-disp);

    font-size:42px;
    font-weight:300;

    margin-bottom:30px;
}

.error{
    background:rgba(255,0,0,0.15);
    color:#ffd1d1;
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    text-align:center;
}

.input-box{
    margin-bottom:18px;
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

.register-btn{

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

.register-btn:hover{

    background:var(--gold2);

    transform:translateY(-2px);
}

.login-link{

    text-align:center;

    margin-top:22px;
}

.login-link a{

    color:var(--gold2);

    text-decoration:none;
}

.login-link a:hover{

    text-decoration:underline;
}

.back-home{

    text-align:center;

    margin-top:12px;
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

<div class="register-card">

    <div class="logo">
        Skyvelle
    </div>

    <div class="title">
        Create Your Account
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
                   name="customer_id"
                   placeholder="Choose a User ID"
                   required>
        </div>

        <div class="input-box">
            <label>Full Name</label>
            <input type="text"
                   name="name"
                   placeholder="Enter Full Name"
                   required>
        </div>

        <div class="input-box">
            <label>Email Address</label>
            <input type="email"
                   name="email"
                   placeholder="Enter Email"
                   required>
        </div>

        <div class="input-box">
            <label>Password</label>
            <input type="password"
                   name="password"
                   placeholder="Create Password"
                   required>
        </div>

        <div class="input-box">
            <label>Phone Number</label>
            <input type="text"
                   name="phone_no"
                   placeholder="Enter Phone Number"
                   required>
        </div>

        <div class="input-box">
            <label>Address</label>
            <input type="text"
                   name="address"
                   placeholder="Enter Address"
                   required>
        </div>

        <button type="submit" class="register-btn">
            Create Account
        </button>

    </form>

    <div class="login-link">
        Already have an account?
        <a href="login_page.php">Login</a>
    </div>

    <div class="back-home">
        <a href="index.php">← Back to Home</a>
    </div>

</div>

</body>
</html>