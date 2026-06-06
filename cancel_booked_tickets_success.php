<?php
session_start();
$refund = $_SESSION['refund_amount'] ?? 0;
unset($_SESSION['refund_amount']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cancellation Successful — Skyvelle</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
body{
    background:
    linear-gradient(rgba(3,8,25,0.93),rgba(3,8,25,0.96)),
    url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
    background-size:cover; background-position:center; background-attachment:fixed;
    color:white; min-height:100vh;
    display:flex; align-items:center; justify-content:center; padding:30px;
}
.card{
    background:rgba(255,255,255,0.07);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.09);
    border-radius:28px;
    padding:56px 48px;
    max-width:520px;
    width:100%;
    text-align:center;
}
.icon-wrap{
    width:80px; height:80px;
    border-radius:50%;
    background:rgba(34,197,94,0.15);
    border:2px solid rgba(34,197,94,0.35);
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 28px;
    font-size:34px; color:#4ade80;
}
h2{ font-size:30px; font-weight:700; margin-bottom:12px; }
.sub{ color:#94a3b8; font-size:15px; margin-bottom:36px; line-height:1.6; }
.refund-box{
    background:rgba(34,197,94,0.1);
    border:1px solid rgba(34,197,94,0.25);
    border-radius:16px;
    padding:24px 32px;
    margin-bottom:36px;
}
.refund-label{ font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#4ade80; margin-bottom:8px; }
.refund-amount{ font-size:40px; font-weight:700; color:#4ade80; }
.refund-note{ font-size:12px; color:#64748b; margin-top:8px; }
.actions{ display:flex; flex-direction:column; gap:12px; }
.btn-primary{
    display:flex; align-items:center; justify-content:center; gap:10px;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white; text-decoration:none;
    padding:14px 28px; border-radius:14px; font-weight:600; font-size:15px;
    transition:0.3s;
}
.btn-primary:hover{ transform:translateY(-2px); box-shadow:0 8px 20px rgba(67,97,238,0.4); }
.btn-secondary{
    display:flex; align-items:center; justify-content:center; gap:10px;
    background:rgba(255,255,255,0.06); color:white; text-decoration:none;
    padding:14px 28px; border-radius:14px; font-size:15px;
    border:1px solid rgba(255,255,255,0.1); transition:0.3s;
}
.btn-secondary:hover{ background:rgba(255,255,255,0.1); }
</style>
</head>
<body>
<div class="card">
    <div class="icon-wrap"><i class="fa-solid fa-check"></i></div>
    <h2>Booking Cancelled</h2>
    <p class="sub">Your ticket has been successfully cancelled. Your refund will be processed within 5–7 business days.</p>

    <?php if($refund > 0): ?>
    <div class="refund-box">
        <div class="refund-label"><i class="fa-solid fa-indian-rupee-sign"></i> Refund Amount</div>
        <div class="refund-amount">₹<?= number_format($refund, 2) ?></div>
        <div class="refund-note">15% cancellation charge has been deducted</div>
    </div>
    <?php else: ?>
    <div class="refund-box">
        <div class="refund-label">Refund</div>
        <div style="font-size:15px;color:#94a3b8;padding:6px 0;">Refund details will be processed separately.</div>
    </div>
    <?php endif; ?>

    <div class="actions">
        <a href="view_booked_tickets.php" class="btn-primary">
            <i class="fa-solid fa-ticket"></i> View My Bookings
        </a>
        <a href="homepage.php#quick-actions" class="btn-secondary">
            <i class="fa-solid fa-house"></i> Back to Dashboard
        </a>
    </div>
</div>
</body>
</html>