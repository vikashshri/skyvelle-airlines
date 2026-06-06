<?php
session_start();

if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['pnr'])){
    header("Location: view_booked_tickets.php");
    exit();
}

$pnr         = $_GET['pnr'];
$customer_id = $_SESSION['userid'];

require_once('Database Connection file/mysqli_connect.php');

/* ── Ticket + Flight details ── */

$q = "SELECT
        t.pnr, t.date_of_reservation, t.flight_no, t.journey_date,
        t.class, t.booking_status, t.no_of_passengers,
        t.lounge_access, t.priority_checkin, t.insurance, t.payment_id,
        f.from_city, f.to_city, f.departure_time, f.arrival_time,
        f.arrival_date, f.price_economy, f.price_business, f.jet_id
      FROM ticket_details t
      LEFT JOIN flight_details f
        ON t.flight_no = f.flight_no AND t.journey_date = f.departure_date
      WHERE t.pnr = ? AND t.customer_id = ?";

$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "ss", $pnr, $customer_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if(!$ticket){
    die("Ticket not found.");
}

/* ── Passengers ── */

/* ── Passengers ── */

$pq = "SELECT
            name,
            age,
            gender,
            seat_no,
            meal_choice,
            frequent_flier_no
        FROM passengers
        WHERE pnr = ?";

$pst = mysqli_prepare($dbc, $pq);

if (!$pst) {
    die("Passenger query error: " . mysqli_error($dbc));
}

mysqli_stmt_bind_param($pst, "s", $pnr);
mysqli_stmt_execute($pst);

$pres = mysqli_stmt_get_result($pst);

$passengers = [];

while ($row = mysqli_fetch_assoc($pres)) {
    $passengers[] = $row;
}

mysqli_stmt_close($pst);

/* ── Customer name ── */

$cq = "SELECT name FROM customer WHERE customer_id = ?";
$cst = mysqli_prepare($dbc, $cq);
mysqli_stmt_bind_param($cst, "s", $customer_id);
mysqli_stmt_execute($cst);
mysqli_stmt_bind_result($cst, $cust_name);
mysqli_stmt_fetch($cst);
mysqli_stmt_close($cst);

mysqli_close($dbc);

/* ── Price ── */
$price_per = strtolower($ticket['class']) === 'business'
             ? $ticket['price_business']
             : $ticket['price_economy'];
$price_per  = $price_per ?? 5000;
$total      = $price_per * $ticket['no_of_passengers'];

/* ── Badge ── */
$bs  = strtolower($ticket['booking_status']);
$bcolor = '#fbbf24';
if(str_contains($bs,'confirm')) $bcolor = '#4ade80';
if(str_contains($bs,'cancel'))  $bcolor = '#f87171';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ticket Receipt — <?= htmlspecialchars($pnr) ?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<style>

*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }

body{
    background:
    linear-gradient(rgba(3,8,25,0.93), rgba(3,8,25,0.96)),
    url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
    color:white;
    min-height:100vh;
    padding:30px;
}

/* NAVBAR */

.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 28px;
    margin-bottom:30px;
}


.nav-links{ display:flex; gap:12px; }

.nav-links a{
    text-decoration:none;
    color:white;
    padding:10px 16px;
    border-radius:10px;
    transition:0.3s;
    font-size:20px;
}

.nav-links a:hover{ background:linear-gradient(135deg,#4361ee,#7209b7); }

/* ACTION BUTTONS */

.action-bar{
    display:flex;
    gap:14px;
    margin-bottom:28px;
    flex-wrap:wrap;
}

.btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:12px 24px;
    border-radius:14px;
    font-size:14px;
    font-weight:600;
    font-family:'Poppins',sans-serif;
    cursor:pointer;
    border:none;
    transition:0.3s;
    text-decoration:none;
}

.btn-back{
    background:rgba(255,255,255,0.08);
    color:white;
    border:1px solid rgba(255,255,255,0.12);
}

.btn-back:hover{ background:rgba(255,255,255,0.14); }

.btn-download{
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white;
}

.btn-download:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(67,97,238,0.4);
}

/* ═══════════════════════════
   TICKET / RECEIPT STYLES
   (these also apply in print)
═══════════════════════════ */

.receipt-wrap{
    max-width:780px;
    margin:0 auto;
}

.receipt{
    background:white;
    color:#1e293b;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 20px 60px rgba(0,0,0,0.5);
}

/* TOP HEADER */

.receipt-header{
    background:linear-gradient(135deg,#1d4ed8,#7e22ce);
    color:white;
    padding:28px 36px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.receipt-header .airline{
    font-size:26px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
}

.receipt-header .ticket-label{
    font-size:13px;
    opacity:0.8;
    text-align:right;
}

.receipt-header .ticket-label strong{
    display:block;
    font-size:16px;
    opacity:1;
}

/* PASSENGER BANNER */

.passenger-banner{
    background:#f8fafc;
    padding:20px 36px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:2px dashed #e2e8f0;
}

.passenger-banner .p-name{
    font-size:28px;
    font-weight:700;
    color:#0f172a;
    font-family:'Playfair Display',serif;
}

.passenger-banner .p-pnr{
    font-size:13px;
    color:#64748b;
}

.passenger-banner .p-pnr strong{
    font-size:18px;
    color:#1d4ed8;
    display:block;
}

/* STATUS BADGE */

.status-pill{
    display:inline-block;
    padding:5px 16px;
    border-radius:99px;
    font-size:12px;
    font-weight:700;
    letter-spacing:0.5px;
}

/* ROUTE SECTION */

.route-section{
    padding:24px 36px;
    display:grid;
    grid-template-columns:1fr auto 1fr;
    align-items:center;
    gap:16px;
    border-bottom:1px solid #e2e8f0;
    background:white;
}

.route-city .label{ font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px; }
.route-city .city{ font-size:24px; font-weight:700; color:#0f172a; }
.route-city .date{ font-size:13px; color:#64748b; margin-top:4px; }
.route-city .time{ font-size:20px; font-weight:600; color:#1d4ed8; margin-top:2px; }

.route-arrow{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
    color:#94a3b8;
}

.route-arrow i{ font-size:28px; color:#4361ee; }
.route-arrow .flight-no{ font-size:12px; font-weight:600; color:#7209b7; background:#f3e8ff; padding:3px 10px; border-radius:99px; }

/* INFO GRID */

.info-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:0;
    border-bottom:1px solid #e2e8f0;
}

.info-cell{
    padding:18px 20px;
    border-right:1px solid #e2e8f0;
}

.info-cell:last-child{ border-right:none; }
.info-cell .lbl{ font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:5px; }
.info-cell .val{ font-size:15px; font-weight:600; color:#0f172a; }

/* ADDONS */

.addons{
    padding:16px 36px;
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    border-bottom:1px solid #e2e8f0;
    background:#f8fafc;
}

.addon-chip{
    display:flex;
    align-items:center;
    gap:6px;
    padding:6px 14px;
    border-radius:99px;
    font-size:12px;
    font-weight:600;
}

.addon-yes{ background:#dcfce7; color:#166534; }
.addon-no{  background:#f1f5f9; color:#94a3b8; }

/* PASSENGERS TABLE */

.section-head{
    padding:18px 36px 10px;
    font-size:13px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:1px;
    color:#64748b;
    background:white;
}

.pass-table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

.pass-table th{
    padding:10px 20px;
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:0.8px;
    color:#94a3b8;
    text-align:left;
    border-bottom:1px solid #e2e8f0;
}

.pass-table td{
    padding:12px 20px;
    font-size:14px;
    color:#1e293b;
    border-bottom:1px solid #f1f5f9;
}

.pass-table tr:last-child td{ border-bottom:none; }

.pass-num{
    width:28px; height:28px;
    border-radius:50%;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white;
    font-size:12px;
    font-weight:700;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

/* PRICE FOOTER */

.price-footer{
    background:linear-gradient(135deg,#1e293b,#0f172a);
    color:white;
    padding:22px 36px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.price-breakdown{ font-size:13px; color:#94a3b8; }
.price-breakdown strong{ color:white; }

.total-price{
    text-align:right;
}

.total-price .label{ font-size:12px; color:#94a3b8; }
.total-price .amount{ font-size:30px; font-weight:700; color:#4cc9f0; }

/* TEAR LINE */

.tear-line{
    border:none;
    border-top:2px dashed #e2e8f0;
    margin:0;
}

/* BARCODE FOOTER */

.barcode-footer{
    background:#f8fafc;
    padding:18px 36px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.barcode-svg{ opacity:0.4; }

.booking-ref{
    text-align:right;
    font-size:12px;
    color:#94a3b8;
}

.booking-ref strong{ display:block; font-size:18px; color:#0f172a; letter-spacing:2px; }

/* ═══════════════════
   PRINT / PDF STYLES
═══════════════════ */

@media print {
    body{
        background:white !important;
        padding:0 !important;
        color:#1e293b !important;
    }
    .navbar, .action-bar{ display:none !important; }
    .receipt-wrap{ max-width:100%; }
    .receipt{
        border-radius:0;
        box-shadow:none;
    }
}

@media(max-width:680px){
    .route-section{ grid-template-columns:1fr; text-align:center; }
    .route-arrow{ transform:rotate(90deg); }
    .info-grid{ grid-template-columns:1fr 1fr; }
    .passenger-banner{ flex-direction:column; gap:10px; text-align:center; }
    .receipt-header{ flex-direction:column; gap:10px; text-align:center; }
    .price-footer{ flex-direction:column; gap:12px; text-align:center; }
    .barcode-footer{ flex-direction:column; gap:12px; }
}

</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-links">
        <a href="view_booked_tickets.php"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</div>

<!-- ACTION BAR -->
<div class="receipt-wrap">
<div class="action-bar">
    <button class="btn btn-download" onclick="window.print()">
        <i class="fa-solid fa-download"></i> Download / Print Receipt
    </button>
</div>

<!-- RECEIPT -->
<div class="receipt">

    <!-- HEADER -->
    <div class="receipt-header">
        <div class="airline">
            <i class="fa-solid fa-plane"></i>
            Skyvelle Airlines
        </div>
        <div class="ticket-label">
            BOARDING PASS / E-TICKET
            <strong>✈ Have a great flight!</strong>
        </div>
    </div>

    <!-- PASSENGER BANNER -->
    <div class="passenger-banner">
        <div>
            <div class="p-name"><?= htmlspecialchars($cust_name ?? $customer_id) ?></div>
            <div class="p-pnr" style="margin-top:4px;">
                Booked on <?= htmlspecialchars($ticket['date_of_reservation']) ?>
            </div>
        </div>
        <div style="text-align:right;">
            <div class="p-pnr">PNR / Booking Reference
                <strong><?= htmlspecialchars($pnr) ?></strong>
            </div>
            <div style="margin-top:8px;">
                <span class="status-pill" style="background:<?= $bcolor ?>22;color:<?= $bcolor ?>;border:1px solid <?= $bcolor ?>44;">
                    <?= htmlspecialchars(strtoupper($ticket['booking_status'])) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- ROUTE -->
    <div class="route-section">
        <div class="route-city">
            <div class="label">Departure</div>
            <div class="city"><?= htmlspecialchars(strtoupper($ticket['from_city'] ?? '—')) ?></div>
            <div class="date"><?= htmlspecialchars($ticket['journey_date']) ?></div>
            <div class="time"><?= htmlspecialchars($ticket['departure_time'] ?? '—') ?></div>
        </div>

        <div class="route-arrow">
            <i class="fa-solid fa-plane"></i>
            <div class="flight-no"><?= htmlspecialchars($ticket['flight_no']) ?></div>
        </div>

        <div class="route-city" style="text-align:right;">
            <div class="label">Arrival</div>
            <div class="city"><?= htmlspecialchars(strtoupper($ticket['to_city'] ?? '—')) ?></div>
            <div class="date"><?= htmlspecialchars($ticket['arrival_date'] ?? '—') ?></div>
            <div class="time"><?= htmlspecialchars($ticket['arrival_time'] ?? '—') ?></div>
        </div>
    </div>

    <!-- INFO GRID -->
    <div class="info-grid">
        <div class="info-cell">
            <div class="lbl">Flight No.</div>
            <div class="val"><?= htmlspecialchars($ticket['flight_no']) ?></div>
        </div>
        <div class="info-cell">
            <div class="lbl">Class</div>
            <div class="val"><?= ucfirst(htmlspecialchars($ticket['class'])) ?></div>
        </div>
        <div class="info-cell">
            <div class="lbl">Passengers</div>
            <div class="val"><?= htmlspecialchars($ticket['no_of_passengers']) ?></div>
        </div>
        <div class="info-cell">
            <div class="lbl">Aircraft</div>
            <div class="val"><?= htmlspecialchars($ticket['jet_id'] ?? '—') ?></div>
        </div>
    </div>

    <!-- ADD-ONS -->
    <div class="addons">
        <div class="addon-chip <?= $ticket['lounge_access']==='yes' ? 'addon-yes' : 'addon-no' ?>">
            <i class="fa-solid fa-couch"></i>
            Lounge Access: <?= strtoupper($ticket['lounge_access']) ?>
        </div>
        <div class="addon-chip <?= $ticket['priority_checkin']==='yes' ? 'addon-yes' : 'addon-no' ?>">
            <i class="fa-solid fa-star"></i>
            Priority Check-in: <?= strtoupper($ticket['priority_checkin']) ?>
        </div>
        <div class="addon-chip <?= $ticket['insurance']==='yes' ? 'addon-yes' : 'addon-no' ?>">
            <i class="fa-solid fa-shield-halved"></i>
            Travel Insurance: <?= strtoupper($ticket['insurance']) ?>
        </div>
        <?php if($ticket['payment_id']): ?>
        <div class="addon-chip addon-yes">
            <i class="fa-solid fa-credit-card"></i>
            Payment ID: <?= htmlspecialchars($ticket['payment_id']) ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- PASSENGERS TABLE -->
    <?php if(!empty($passengers)): ?>
    <div class="section-head">Passenger Details</div>
    <table class="pass-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Seat</th>
                <th>Meal</th>
                <th>FF Number</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($passengers as $i => $p): ?>
        <tr>
            <td><span class="pass-num"><?= $i+1 ?></span></td>
            <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
            <td><?= htmlspecialchars($p['age']) ?></td>
            <td><?= ucfirst(htmlspecialchars($p['gender'])) ?></td>
           <td>
    <strong style="color:#1d4ed8;">
        <?= !empty($p['seat_no']) ? htmlspecialchars($p['seat_no']) : '—' ?>
    </strong>
</td>
            <td>
                <?php if($p['meal_choice']==='yes'): ?>
                <span style="color:#16a34a;font-weight:600;">✓ Yes</span>
                <?php else: ?>
                <span style="color:#94a3b8;">No</span>
                <?php endif; ?>
            </td>
            <td><?= $p['frequent_flier_no'] ? htmlspecialchars($p['frequent_flier_no']) : '<span style="color:#94a3b8;">—</span>' ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- TEAR LINE -->
    <hr class="tear-line">

    <!-- PRICE FOOTER -->
    <div class="price-footer">
        <div class="price-breakdown">
            <strong>₹<?= number_format($price_per) ?></strong> × <?= $ticket['no_of_passengers'] ?> passenger(s)
            &nbsp;·&nbsp; <?= ucfirst($ticket['class']) ?> Class
        </div>
        <div class="total-price">
            <div class="label">Total Fare</div>
            <div class="amount">₹<?= number_format($total) ?></div>
        </div>
    </div>

    <!-- BARCODE FOOTER -->
    <div class="barcode-footer">
        <!-- Simple SVG barcode simulation -->
        <svg class="barcode-svg" width="180" height="40" viewBox="0 0 180 40">
            <?php
            srand(crc32($pnr));
            $x = 0;
            for($b = 0; $b < 60; $b++){
                $w = rand(1,4);
                if($b % 2 == 0){
                    echo '<rect x="'.$x.'" y="0" width="'.$w.'" height="40" fill="#0f172a"/>';
                }
                $x += $w + 1;
            }
            ?>
        </svg>
        <div class="booking-ref">
            Booking Reference
            <strong><?= htmlspecialchars($pnr) ?></strong>
            <div style="font-size:11px;margin-top:4px;">Skyvelle Airlines · E-Ticket</div>
        </div>
    </div>

</div><!-- end receipt -->
</div><!-- end receipt-wrap -->

</body>
</html>