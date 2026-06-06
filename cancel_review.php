<?php
session_start();
if(!isset($_SESSION['userid'])){ header("Location: login.php"); exit(); }
if(!isset($_POST['pnr'])){ header("Location: cancel_booked_tickets.php"); exit(); }

$pnr         = trim($_POST['pnr']);
$customer_id = $_SESSION['userid'];
$todays_date = date('Y-m-d');

require_once('Database Connection file/mysqli_connect.php');

/* fetch ticket + flight + payment */
$q = "SELECT t.pnr, t.flight_no, t.journey_date, t.class,
             t.booking_status, t.no_of_passengers,
             t.lounge_access, t.priority_checkin, t.insurance,
             t.date_of_reservation,
             f.from_city, f.to_city, f.departure_time,
             p.payment_amount,
             (0.85 * p.payment_amount) AS refund_amount
      FROM ticket_details t
      LEFT JOIN flight_details f ON f.flight_no = t.flight_no AND f.departure_date = t.journey_date
      LEFT JOIN payment_details p ON p.pnr = t.pnr
      WHERE t.pnr=? AND t.customer_id=? AND t.journey_date>=?";

$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "sss", $pnr, $customer_id, $todays_date);
mysqli_stmt_execute($stmt);
$res  = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
mysqli_close($dbc);

if(!$ticket || strtoupper($ticket['booking_status']) === 'CANCELLED'){
    header("Location: cancel_booked_tickets.php?msg=failed"); exit();
}

$_SESSION['cancel_pnr'] = $pnr;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Review Booking — Skyvelle</title>
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
    padding:16px 28px; 20px; margin-bottom:30px;
}
.logo{ font-size:26px; font-weight:700; }
.nav-links{ display:flex; gap:12px; }
.nav-links a{ text-decoration:none; color:white; padding:10px 16px; border-radius:10px; transition:0.3s; font-size:14px; }
.nav-links a:hover{ background:linear-gradient(135deg,#4361ee,#7209b7); }

.steps-bar{
    display:flex; align-items:center; justify-content:center;
    gap:0; margin-bottom:36px; max-width:600px; margin-left:auto; margin-right:auto;
}
.step{ display:flex; flex-direction:column; align-items:center; gap:6px; flex:1; }
.step-circle{
    width:38px; height:38px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:700;
    border:2px solid rgba(255,255,255,0.15);
    background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.3);
}
.step.active .step-circle{ background:linear-gradient(135deg,#4361ee,#7209b7); border-color:transparent; color:white; box-shadow:0 0 20px rgba(67,97,238,0.5); }
.step.done .step-circle{ background:rgba(34,197,94,0.2); border-color:rgba(34,197,94,0.4); color:#4ade80; }
.step-label{ font-size:11px; color:rgba(255,255,255,0.4); }
.step.active .step-label{ color:white; }
.step.done .step-label{ color:#4ade80; }
.step-line{ flex:1; height:2px; background:rgba(255,255,255,0.08); margin-bottom:22px; max-width:80px; }
.step-line.done{ background:rgba(34,197,94,0.4); }

.card{
    background:rgba(255,255,255,0.07); backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.09); border-radius:26px;
    padding:40px; max-width:620px; margin:0 auto;
}
.card-title{ font-size:22px; font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:12px; }
.card-title i{ color:#4cc9f0; }
.card-sub{ color:#64748b; font-size:14px; margin-bottom:32px; }

/* ROUTE */
.route-row{
    display:flex; align-items:center; justify-content:space-between;
    background:linear-gradient(135deg,rgba(29,78,216,0.2),rgba(126,34,206,0.2));
    border:1px solid rgba(255,255,255,0.08); border-radius:18px;
    padding:24px 28px; margin-bottom:24px;
}
.route-city .lbl{ font-size:11px; color:#94a3b8; margin-bottom:4px; letter-spacing:1px; }
.route-city .city{ font-size:22px; font-weight:700; }
.route-city .time{ font-size:13px; color:#4cc9f0; margin-top:4px; }
.route-mid{ text-align:center; }
.route-mid i{ font-size:24px; color:#4cc9f0; display:block; margin-bottom:6px; }
.route-mid .fn{ font-size:12px; color:#64748b; }

/* INFO GRID */
.info-grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px; }
.info-item{
    background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07);
    border-radius:14px; padding:16px 18px;
}
.info-item .lbl{ font-size:11px; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:5px; }
.info-item .val{ font-size:15px; font-weight:600; }

/* REFUND BOX */
.refund-box{
    background:rgba(234,179,8,0.08); border:1px solid rgba(234,179,8,0.25);
    border-radius:16px; padding:20px 24px; margin-bottom:28px;
    display:flex; align-items:center; justify-content:space-between;
}
.rb-left .lbl{ font-size:12px; color:#fbbf24; margin-bottom:4px; }
.rb-left .note{ font-size:12px; color:#64748b; }
.rb-right .amount{ font-size:28px; font-weight:700; color:#fbbf24; }
.rb-right .orig{ font-size:12px; color:#64748b; text-decoration:line-through; }

.btn-next{
    width:100%; padding:16px;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white; border:none; border-radius:14px;
    font-family:'Poppins',sans-serif; font-size:16px; font-weight:600;
    cursor:pointer; transition:0.3s;
    display:flex; align-items:center; justify-content:center; gap:10px;
}
.btn-next:hover{ transform:translateY(-2px); box-shadow:0 10px 25px rgba(67,97,238,0.4); }
.btn-back{
    display:flex; align-items:center; justify-content:center; gap:8px;
    color:#64748b; text-decoration:none; font-size:14px; margin-top:14px;
    transition:0.2s; text-align:center;
}
.btn-back:hover{ color:white; }

.status-pill{
    display:inline-block; padding:4px 14px; border-radius:99px;
    font-size:12px; font-weight:700;
    background:rgba(234,179,8,0.15); color:#fde68a;
    border:1px solid rgba(234,179,8,0.3);
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

<div class="steps-bar">
    <div class="step done"><div class="step-circle"><i class="fa-solid fa-check" style="font-size:12px;"></i></div><div class="step-label">Enter PNR</div></div>
    <div class="step-line done"></div>
    <div class="step active"><div class="step-circle">2</div><div class="step-label">Review</div></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">3</div><div class="step-label">Reason</div></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">4</div><div class="step-label">Confirm</div></div>
</div>

<div class="card">
    <div class="card-title"><i class="fa-solid fa-ticket"></i> Review Your Booking</div>
    <div class="card-sub">Please verify the details below before proceeding with cancellation.</div>

    <div class="route-row">
        <div class="route-city">
            <div class="lbl">FROM</div>
            <div class="city"><?= htmlspecialchars(strtoupper($ticket['from_city'] ?? '—')) ?></div>
            <div class="time"><?= htmlspecialchars($ticket['departure_time'] ?? '—') ?></div>
        </div>
        <div class="route-mid">
            <i class="fa-solid fa-plane"></i>
            <div class="fn"><?= htmlspecialchars($ticket['flight_no']) ?></div>
        </div>
        <div class="route-city" style="text-align:right;">
            <div class="lbl">TO</div>
            <div class="city"><?= htmlspecialchars(strtoupper($ticket['to_city'] ?? '—')) ?></div>
            <div class="time"><?= htmlspecialchars($ticket['journey_date']) ?></div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="lbl">PNR</div>
            <div class="val"><?= htmlspecialchars($ticket['pnr']) ?></div>
        </div>
        <div class="info-item">
            <div class="lbl">Status</div>
            <div class="val"><span class="status-pill"><?= htmlspecialchars($ticket['booking_status']) ?></span></div>
        </div>
        <div class="info-item">
            <div class="lbl">Class</div>
            <div class="val"><?= ucfirst(htmlspecialchars($ticket['class'])) ?></div>
        </div>
        <div class="info-item">
            <div class="lbl">Passengers</div>
            <div class="val"><?= htmlspecialchars($ticket['no_of_passengers']) ?></div>
        </div>
        <div class="info-item">
            <div class="lbl">Booked On</div>
            <div class="val"><?= htmlspecialchars($ticket['date_of_reservation']) ?></div>
        </div>
        <div class="info-item">
            <div class="lbl">Journey Date</div>
            <div class="val"><?= htmlspecialchars($ticket['journey_date']) ?></div>
        </div>
    </div>

    <div class="refund-box">
        <div class="rb-left">
            <div class="lbl"><i class="fa-solid fa-indian-rupee-sign"></i> Estimated Refund</div>
            <div class="note">15% cancellation fee deducted</div>
        </div>
        <div class="rb-right">
            <?php if($ticket['payment_amount']): ?>
            <div class="orig">₹<?= number_format($ticket['payment_amount'], 2) ?></div>
            <div class="amount">₹<?= number_format($ticket['refund_amount'], 2) ?></div>
            <?php else: ?>
            <div class="amount" style="font-size:16px;color:#64748b;">Pending payment</div>
            <?php endif; ?>
        </div>
    </div>

    <form action="cancel_reason.php" method="post">
        <input type="hidden" name="pnr" value="<?= htmlspecialchars($pnr) ?>">
        <button type="submit" class="btn-next">
            <i class="fa-solid fa-arrow-right"></i> Continue to Select Reason
        </button>
    </form>
    <a href="cancel_booked_tickets.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>
</body>
</html>
