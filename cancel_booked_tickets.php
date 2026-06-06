<?php
session_start();
if(!isset($_SESSION['userid'])){
    header("Location: login.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cancel Ticket — Skyvelle</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
body{
    background:linear-gradient(rgba(3,8,25,0.93),rgba(3,8,25,0.96)),
    url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
    background-size:cover; background-position:center; background-attachment:fixed;
    color:white; min-height:100vh; padding:30px;
}
.navbar{
    display:flex; justify-content:space-between; align-items:center;
    padding:16px 28px;  margin-bottom:30px;
}
.logo{ font-size:26px; font-weight:700; }
.nav-links{ display:flex; gap:12px; }
.nav-links a{
    text-decoration:none; color:white; padding:10px 16px;
    border-radius:10px; transition:0.3s; font-size:14px;
}
.nav-links a:hover{ background:linear-gradient(135deg,#4361ee,#7209b7); }

/* STEPS */
.steps-bar{
    display:flex; align-items:center; justify-content:center;
    gap:0; margin-bottom:36px; max-width:600px; margin-left:auto; margin-right:auto;
}
.step{
    display:flex; flex-direction:column; align-items:center; gap:6px;
    flex:1;
}
.step-circle{
    width:38px; height:38px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:700;
    border:2px solid rgba(255,255,255,0.15);
    background:rgba(255,255,255,0.05);
    color:rgba(255,255,255,0.3);
    transition:0.3s;
    position:relative; z-index:1;
}
.step.active .step-circle{
    background:linear-gradient(135deg,#4361ee,#7209b7);
    border-color:transparent; color:white;
    box-shadow:0 0 20px rgba(67,97,238,0.5);
}
.step.done .step-circle{
    background:rgba(34,197,94,0.2);
    border-color:rgba(34,197,94,0.4); color:#4ade80;
}
.step-label{ font-size:11px; color:rgba(255,255,255,0.4); letter-spacing:0.5px; }
.step.active .step-label{ color:white; }
.step.done .step-label{ color:#4ade80; }
.step-line{
    flex:1; height:2px; background:rgba(255,255,255,0.08);
    margin-bottom:22px; max-width:80px;
}
.step-line.done{ background:rgba(34,197,94,0.4); }

/* CARD */
.card{
    background:rgba(255,255,255,0.07);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.09);
    border-radius:26px; padding:40px;
    max-width:580px; margin:0 auto;
}
.card-title{
    font-size:22px; font-weight:700; margin-bottom:8px;
    display:flex; align-items:center; gap:12px;
}
.card-title i{ color:#4cc9f0; }
.card-sub{ color:#64748b; font-size:14px; margin-bottom:32px; }

/* INPUT */
.field{ margin-bottom:24px; }
.field label{
    display:block; font-size:12px; letter-spacing:1.5px;
    text-transform:uppercase; color:#94a3b8; margin-bottom:10px;
}
.field input{
    width:100%; padding:16px 18px;
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:14px; color:white;
    font-family:'Poppins',sans-serif; font-size:16px; outline:none;
    transition:0.2s;
}
.field input:focus{ border-color:rgba(76,201,240,0.4); background:rgba(76,201,240,0.05); }
.field input::placeholder{ color:rgba(255,255,255,0.25); }

.btn-main{
    width:100%; padding:16px;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white; border:none; border-radius:14px;
    font-family:'Poppins',sans-serif; font-size:16px; font-weight:600;
    cursor:pointer; transition:0.3s;
    display:flex; align-items:center; justify-content:center; gap:10px;
}
.btn-main:hover{ transform:translateY(-2px); box-shadow:0 10px 25px rgba(67,97,238,0.4); }

.error-box{
    background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.3);
    border-radius:12px; padding:14px 18px; margin-bottom:22px;
    font-size:14px; color:#fca5a5;
    display:flex; align-items:center; gap:10px;
}
</style>
</head>
<body>
<div class="navbar">
    <div class="logo">✈ Skyvelle</div>
    <div class="nav-links">
        <a href="homepage.php"><i class="fa-solid fa-house"></i> Home</a>
        <a href="view_booked_tickets.php"><i class="fa-solid fa-ticket"></i> My Tickets</a>
    </div>
</div>

<!-- STEPS -->
<div class="steps-bar">
    <div class="step active"><div class="step-circle">1</div><div class="step-label">Enter PNR</div></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">2</div><div class="step-label">Review</div></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">3</div><div class="step-label">Reason</div></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">4</div><div class="step-label">Confirm</div></div>
</div>

<div class="card">
    <div class="card-title"><i class="fa-solid fa-magnifying-glass"></i> Find Your Booking</div>
    <div class="card-sub">Enter the PNR number from your booking confirmation to get started.</div>

    <?php if(isset($_GET['msg']) && $_GET['msg']=='failed'): ?>
    <div class="error-box"><i class="fa-solid fa-circle-exclamation"></i> Invalid PNR or ticket not found. Please check and try again.</div>
    <?php endif; ?>

    <form action="cancel_review.php" method="post">
        <div class="field">
            <label>PNR Number</label>
            <input type="text" name="pnr" placeholder="e.g. 6118602" required>
        </div>
        <button type="submit" name="find_ticket" class="btn-main">
            <i class="fa-solid fa-arrow-right"></i> Find My Ticket
        </button>
    </form>
</div>
</body>
</html>