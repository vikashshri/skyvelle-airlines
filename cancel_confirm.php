<?php
session_start();
if(!isset($_SESSION['userid'])){ header("Location: login.php"); exit(); }
if(!isset($_POST['pnr']) || !isset($_POST['reason'])){ header("Location: cancel_booked_tickets.php"); exit(); }

$pnr    = trim($_POST['pnr']);
$reason = $_POST['reason'] === 'Other'
          ? trim($_POST['other_reason'] ?? 'Other')
          : trim($_POST['reason']);

$_SESSION['cancel_pnr']    = $pnr;
$_SESSION['cancel_reason'] = $reason;
$customer_id = $_SESSION['userid'];
$todays_date = date('Y-m-d');

require_once('Database Connection file/mysqli_connect.php');

$q = "SELECT t.pnr, t.flight_no, t.journey_date, t.class,
             t.no_of_passengers, t.booking_status,
             f.from_city, f.to_city, f.departure_time,
             p.payment_amount, (0.85 * p.payment_amount) AS refund_amount
      FROM ticket_details t
      LEFT JOIN flight_details f ON f.flight_no=t.flight_no AND f.departure_date=t.journey_date
      LEFT JOIN payment_details p ON p.pnr=t.pnr
      WHERE t.pnr=? AND t.customer_id=?";
$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "ss", $pnr, $customer_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
mysqli_close($dbc);

if(!$ticket){ header("Location: cancel_booked_tickets.php?msg=failed"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirm Cancellation — Skyvelle</title>
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
.step.active .step-circle{ background:linear-gradient(135deg,#ef4444,#b91c1c); border-color:transparent; color:white; box-shadow:0 0 20px rgba(239,68,68,0.5); }
.step.done .step-circle{ background:rgba(34,197,94,0.2); border-color:rgba(34,197,94,0.4); color:#4ade80; }
.step-label{ font-size:11px; color:rgba(255,255,255,0.4); }
.step.active .step-label{ color:white; }
.step.done .step-label{ color:#4ade80; }
.step-line{ flex:1; height:2px; background:rgba(255,255,255,0.08); margin-bottom:22px; max-width:80px; }
.step-line.done{ background:rgba(34,197,94,0.4); }

.card{
    background:rgba(255,255,255,0.07); backdrop-filter:blur(16px);
    border:1px solid rgba(239,68,68,0.2); border-radius:26px;
    padding:40px; max-width:580px; margin:0 auto;
}
.card-title{ font-size:22px; font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:12px; }
.card-title i{ color:#f87171; }
.card-sub{ color:#64748b; font-size:14px; margin-bottom:28px; }

.summary-row{
    display:flex; justify-content:space-between; align-items:center;
    padding:14px 0; border-bottom:1px solid rgba(255,255,255,0.07);
    font-size:14px;
}
.summary-row:last-of-type{ border-bottom:none; }
.summary-row .lbl{ color:#64748b; }
.summary-row .val{ font-weight:600; }

.reason-display{
    background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);
    border-radius:12px; padding:14px 18px; margin:20px 0;
    display:flex; align-items:center; gap:12px; font-size:14px;
}
.reason-display i{ color:#fbbf24; font-size:16px; }

.refund-big{
    background:rgba(234,179,8,0.08); border:1px solid rgba(234,179,8,0.25);
    border-radius:16px; padding:20px 24px; margin:20px 0;
    display:flex; justify-content:space-between; align-items:center;
}
.refund-big .lbl{ color:#fbbf24; font-size:13px; margin-bottom:4px; }
.refund-big .note{ color:#64748b; font-size:12px; }
.refund-big .amount{ font-size:32px; font-weight:700; color:#fbbf24; }

/* CHECKBOX */
.agree-row{
    display:flex; align-items:flex-start; gap:14px;
    background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.2);
    border-radius:14px; padding:16px 18px; margin:20px 0; cursor:pointer;
}
.agree-row input[type="checkbox"]{ width:18px; height:18px; flex-shrink:0; margin-top:2px; cursor:pointer; accent-color:#ef4444; }
.agree-row label{ font-size:13px; color:#fca5a5; cursor:pointer; line-height:1.5; }

.btn-cancel-final{
    width:100%; padding:16px;
    background:linear-gradient(135deg,#ef4444,#b91c1c);
    color:white; border:none; border-radius:14px;
    font-family:'Poppins',sans-serif; font-size:16px; font-weight:600;
    cursor:pointer; transition:0.3s;
    display:flex; align-items:center; justify-content:center; gap:10px;
    opacity:0.4; pointer-events:none;
}
.btn-cancel-final.ready{ opacity:1; pointer-events:auto; }
.btn-cancel-final.ready:hover{ transform:translateY(-2px); box-shadow:0 10px 25px rgba(239,68,68,0.4); }

.btn-back{ display:flex; align-items:center; justify-content:center; gap:8px; color:#64748b; text-decoration:none; font-size:14px; margin-top:14px; transition:0.2s; text-align:center; }
.btn-back:hover{ color:white; }
</style>
</head>
<body>


<div class="steps-bar">
    <div class="step done"><div class="step-circle"><i class="fa-solid fa-check" style="font-size:12px;"></i></div><div class="step-label">Enter PNR</div></div>
    <div class="step-line done"></div>
    <div class="step done"><div class="step-circle"><i class="fa-solid fa-check" style="font-size:12px;"></i></div><div class="step-label">Review</div></div>
    <div class="step-line done"></div>
    <div class="step done"><div class="step-circle"><i class="fa-solid fa-check" style="font-size:12px;"></i></div><div class="step-label">Reason</div></div>
    <div class="step-line done"></div>
    <div class="step active"><div class="step-circle">4</div><div class="step-label">Confirm</div></div>
</div>

<div class="card">
    <div class="card-title"><i class="fa-solid fa-triangle-exclamation"></i> Confirm Cancellation</div>
    <div class="card-sub">Please review the final summary. This action cannot be undone.</div>

    <div class="summary-row"><span class="lbl">PNR</span><span class="val"><?= htmlspecialchars($ticket['pnr']) ?></span></div>
    <div class="summary-row"><span class="lbl">Flight</span><span class="val"><?= htmlspecialchars($ticket['flight_no']) ?></span></div>
    <div class="summary-row">
        <span class="lbl">Route</span>
        <span class="val"><?= htmlspecialchars(strtoupper($ticket['from_city'] ?? '—')) ?> → <?= htmlspecialchars(strtoupper($ticket['to_city'] ?? '—')) ?></span>
    </div>
    <div class="summary-row"><span class="lbl">Journey Date</span><span class="val"><?= htmlspecialchars($ticket['journey_date']) ?></span></div>
    <div class="summary-row"><span class="lbl">Class</span><span class="val"><?= ucfirst(htmlspecialchars($ticket['class'])) ?></span></div>
    <div class="summary-row"><span class="lbl">Passengers</span><span class="val"><?= htmlspecialchars($ticket['no_of_passengers']) ?></span></div>

    <div class="reason-display">
        <i class="fa-solid fa-circle-info"></i>
        <span>Reason: <strong><?= htmlspecialchars($reason) ?></strong></span>
    </div>

    <div class="refund-big">
        <div>
            <div class="lbl"><i class="fa-solid fa-indian-rupee-sign"></i> Refund Amount</div>
            <div class="note">15% cancellation fee applies</div>
        </div>
        <?php if($ticket['payment_amount']): ?>
        <div class="amount">₹<?= number_format($ticket['refund_amount'], 2) ?></div>
        <?php else: ?>
        <div style="color:#64748b;font-size:14px;">To be calculated</div>
        <?php endif; ?>
    </div>

    <div class="agree-row">
        <input type="checkbox" id="agreeCheck" onchange="toggleBtn(this)">
        <label for="agreeCheck">
            I understand that this cancellation is <strong>irreversible</strong> and a 15% cancellation fee will be deducted from my refund amount.
        </label>
    </div>

    <form action="cancel_booked_tickets_form_handler.php" method="post">
        <input type="hidden" name="pnr" value="<?= htmlspecialchars($pnr) ?>">
        <input type="hidden" name="reason" value="<?= htmlspecialchars($reason) ?>">
        <input type="hidden" name="Cancel_Ticket" value="1">
        <button type="submit" class="btn-cancel-final" id="cancelBtn">
            <i class="fa-solid fa-ban"></i> Yes, Cancel My Booking
        </button>
    </form>
    <a href="cancel_reason.php" class="btn-back" onclick="history.back(); return false;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<script>
function toggleBtn(cb){
    document.getElementById('cancelBtn').classList.toggle('ready', cb.checked);
}
</script>
</body>
</html>
