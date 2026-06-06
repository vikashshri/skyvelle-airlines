<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Skyvelle Airlines</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

:root{
    --gold:#f4b183;
    --dark:#111;
    --ff-disp:'Cormorant Garamond', serif;
    --ff-body:'DM Sans', sans-serif;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:var(--ff-body);
    overflow:hidden;
}

/* HERO */

.hero{
    width:100%;
    height:100vh;
    background:
    linear-gradient(rgba(0,0,0,0.45),
    rgba(0,0,0,0.45)),
    url('https://i.pinimg.com/1200x/50/ab/c2/50abc27dac64985f2f3935ddc74f09b6.jpg');

    background-size:cover;
    background-position:center;
    position:relative;
    color:white;
}

/* NAVBAR */

.navbar{
    width:100%;
    padding:25px 7%;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    font-family:var(--ff-disp);
    font-size:42px;
    font-weight:600;
    color:var(--gold);
}

.nav-links{
    display:flex;
    gap:30px;
}

.nav-links a{
    text-decoration:none;
    color:white;
    font-size:15px;
    letter-spacing:.5px;
}

/* HERO TEXT */

.hero-content{
    position:absolute;
    top:50%;
    left:7%;
    transform:translateY(-50%);
    max-width:650px;
}

.hero-content h2{
    font-family:var(--ff-disp);
    font-size:28px;
    font-weight:300;
    color:#ffd2b0;
    margin-bottom:20px;
}

.hero-content h1{
    font-family:var(--ff-disp);
    font-size:78px;
    font-weight:300;
    line-height:0.95;
    margin-bottom:25px;
}

.hero-content h1 span{
    color:var(--gold);
    font-style:italic;
}

.hero-content p{
    font-size:16px;
    line-height:1.8;
    max-width:500px;
    margin-bottom:35px;
}

.hero-btn{
    display:inline-block;
    padding:11px 36px;
    background:var(--gold);
    color:#222;
    text-decoration:none;
    font-weight:600;
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
}

.input-box input{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    outline:none;
}

.track-btn{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    background:var(--gold);
    font-size:15px;
    font-weight:600;
    cursor:pointer;
}

/* FLOATING STATS */

.floating-box{
    position:absolute;
    bottom:35px;
    left:7%;
    display:flex;
    gap:25px;
}

.info-box{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(10px);
    padding:20px 30px;
    border-radius:18px;
}

.info-box h4{
    font-family:var(--ff-disp);
    font-size:38px;
    font-weight:300;
}

.info-box p{
    font-size:12px;
    letter-spacing:1px;
    margin-top:5px;
}

</style>
</head>

<body>

<section class="hero">

    <div class="navbar">

        <div class="logo">
          <span class="plane-icon"><i class="fa-solid fa-plane"></i></span>  Skyvelle
        </div>

        <div class="nav-links">
            <a href="new_user.php">Register</a>
            <a href="adminlogin.php">Admin</a>
        </div>

    </div>

    <div class="hero-content">

     

        <h1>
            Where Destinations Meet
            <span>Dreams</span>
        </h1>

        <p>
            Discover seamless journeys with Skyvelle Airlines —
            smart booking, real-time tracking and world-class comfort.
        </p>

        <a href="login_page.php" class="hero-btn">
            Login
        </a>

    </div>

    
    <div class="floating-box">

        <div class="info-box">
            <h4>120+</h4>
            <p>GLOBAL DESTINATIONS</p>
        </div>

        <div class="info-box">
            <h4>24/7</h4>
            <p>CUSTOMER SUPPORT</p>
        </div>

        <div class="info-box">
            <h4>98%</h4>
            <p>ON-TIME FLIGHTS</p>
        </div>

    </div>

</section>

</body>
</html>