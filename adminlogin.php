<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "airline_reservation");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $admin_id = $_POST['admin_id'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT * FROM admin
            WHERE admin_id='$admin_id'
            AND pwd='$pwd'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['admin_id'] = $admin_id;

        header("Location: admin_homepage.php");
        exit();

    } else {

        $error = "Invalid Admin ID or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Skyvelle Admin Portal</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

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
    linear-gradient(rgba(0,0,0,0.65),
    rgba(0,0,0,0.65)),
    url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
    background-size:cover;
    background-position:center;

    display:flex;
    justify-content:center;
    align-items:center;

    font-family:var(--ff-body);
}

.admin-card{

    width:430px;

    background:rgba(255,255,255,0.10);
    backdrop-filter:blur(20px);

    border:1px solid rgba(255,255,255,0.15);

    border-radius:28px;

    padding:45px;

    color:white;

    box-shadow:0 15px 40px rgba(0,0,0,0.35);
}

.admin-icon{

    text-align:center;

    font-size:60px;

    color:var(--gold);

    margin-bottom:15px;
}

.title{

    text-align:center;

    font-family:var(--ff-disp);

    font-size:48px;

    font-weight:300;

    margin-bottom:8px;
}

.subtitle{

    text-align:center;

    color:#ddd;

    margin-bottom:30px;

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

.admin-btn{

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

.admin-btn:hover{

    background:var(--gold2);

    transform:translateY(-2px);
}

.back-home{

    text-align:center;

    margin-top:18px;
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

<div class="admin-card">

    <div class="admin-icon">
        <i class="fa-solid fa-user-shield"></i>
    </div>

    <div class="title">
        Admin Login
    </div>

    <div class="subtitle">
        Skyvelle Airline Management Portal
    </div>

    <?php
    if(isset($error)){
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST">

        <div class="input-box">
            <label>Admin ID</label>
            <input type="text"
                   name="admin_id"
                   placeholder="Enter Admin ID"
                   required>
        </div>

        <div class="input-box">
            <label>Password</label>
            <input type="password"
                   name="pwd"
                   placeholder="Enter Password"
                   required>
        </div>

        <button type="submit" class="admin-btn">
            Login as Administrator
        </button>

    </form>

    <div class="back-home">
        <a href="index.php">← Back to Home</a>
    </div>

</div>

</body>
</html>