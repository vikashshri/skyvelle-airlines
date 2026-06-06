<?php
session_start();
/* Uncomment in production:
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }
*/

require_once('Database Connection file/mysqli_connect.php');

/* ── Live Stats ── */

// Total customers
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM customer");
$total_customers = mysqli_fetch_row($r)[0];

// Total bookings
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM ticket_details");
$total_bookings = mysqli_fetch_row($r)[0];

// Total revenue
$r = mysqli_query($dbc,"SELECT COALESCE(SUM(payment_amount),0) FROM payment_details");
$total_revenue = mysqli_fetch_row($r)[0];

// Active flights (departure_date >= today)
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM flight_details WHERE departure_date >= CURDATE()");
$active_flights = mysqli_fetch_row($r)[0];

// Bookings today
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM ticket_details WHERE date_of_reservation = CURDATE()");
$bookings_today = mysqli_fetch_row($r)[0];

// Pending payments
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM ticket_details WHERE booking_status='PENDING'");
$pending = mysqli_fetch_row($r)[0];

// Cancelled bookings
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM ticket_details WHERE booking_status='CANCELLED'");
$cancelled = mysqli_fetch_row($r)[0];

// Confirmed bookings
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM ticket_details WHERE booking_status='CONFIRMED'");
$confirmed = mysqli_fetch_row($r)[0];

// FF members
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM frequent_flier_details");
$ff_members = mysqli_fetch_row($r)[0];

// Total jets
$r = mysqli_query($dbc,"SELECT COUNT(*) FROM jet_details");
$total_jets = mysqli_fetch_row($r)[0];

// Recent 6 bookings
$recent = mysqli_query($dbc,
    "SELECT t.pnr, t.flight_no, t.journey_date, t.class,
            t.booking_status, t.no_of_passengers,
            c.name as cust_name
     FROM ticket_details t
     LEFT JOIN customer c ON c.customer_id = t.customer_id
     ORDER BY t.date_of_reservation DESC, t.pnr DESC
     LIMIT 6");

// Top 4 routes
$routes = mysqli_query($dbc,
    "SELECT f.from_city, f.to_city, COUNT(*) as cnt
     FROM ticket_details t
     JOIN flight_details f ON f.flight_no = t.flight_no
     GROUP BY f.from_city, f.to_city
     ORDER BY cnt DESC
     LIMIT 4");

mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Skyvelle Control</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>

:root{
    --bg:      #070b14;
    --surface: #0d1322;
    --card:    #111827;
    --border:  rgba(255,255,255,0.06);
    --gold:    #c9a84c;
    --gold2:   #e8c97a;
    --sky:     #38bdf8;
    --purple:  #a855f7;
    --green:   #22c55e;
    --red:     #ef4444;
    --amber:   #f59e0b;
    --text:    #e2e8f0;
    --muted:   #475569;
    --ff-head: 'Syne', sans-serif;
    --ff-body: 'DM Sans', sans-serif;
}

*{ margin:0; padding:0; box-sizing:border-box; }
html{ scroll-behavior:smooth; }

body{
    background:var(--bg);
    color:var(--text);
    font-family:var(--ff-body);
    min-height:100vh;
    display:flex;
    overflow-x:hidden;
}

/* ══ SIDEBAR ══ */
.sidebar{
    width:260px;
    min-height:100vh;
    background:var(--surface);
    border-right:1px solid var(--border);
    display:flex;
    flex-direction:column;
    position:fixed;
    top:0; left:0; bottom:0;
    z-index:100;
    transition:0.3s;
}

.sb-logo{
    padding:28px 24px 24px;
    border-bottom:1px solid var(--border);
    display:flex; align-items:center; gap:12px;
}

.sb-logo-icon{
    width:40px; height:40px;
    background:linear-gradient(135deg,var(--gold),var(--gold2));
    border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; color:#0a0e18;
}

.sb-logo-text{
    font-family:var(--ff-head);
    font-size:18px; font-weight:800;
    letter-spacing:0.5px;
}

.sb-logo-text span{ color:var(--gold); }

.sb-section{
    padding:20px 16px 8px;
    font-size:10px;
    letter-spacing:3px;
    text-transform:uppercase;
    color:var(--muted);
    font-weight:600;
}

.sb-link{
    display:flex; align-items:center; gap:12px;
    padding:11px 16px;
    border-radius:10px;
    text-decoration:none;
    color:var(--muted);
    font-size:14px;
    font-weight:500;
    transition:0.2s;
    margin:2px 8px;
}

.sb-link i{
    width:20px; text-align:center; font-size:14px;
    transition:0.2s;
}

.sb-link:hover{
    background:rgba(255,255,255,0.05);
    color:var(--text);
}

.sb-link.active{
    background:rgba(201,168,76,0.12);
    color:var(--gold);
    border:1px solid rgba(201,168,76,0.2);
}

.sb-link.active i{ color:var(--gold); }

.sb-link.danger{ color:#ef444480; }
.sb-link.danger:hover{ background:rgba(239,68,68,0.08); color:#ef4444; }

.sb-bottom{
    margin-top:auto;
    padding:16px;
    border-top:1px solid var(--border);
}

.admin-pill{
    display:flex; align-items:center; gap:10px;
    padding:10px 12px;
    background:rgba(255,255,255,0.04);
    border-radius:10px;
    border:1px solid var(--border);
}

.admin-av{
    width:32px; height:32px; border-radius:8px;
    background:linear-gradient(135deg,#4361ee,var(--purple));
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:700; font-family:var(--ff-head);
    flex-shrink:0;
}

.admin-name{ font-size:13px; font-weight:500; }
.admin-role{ font-size:11px; color:var(--muted); }

/* ══ MAIN ══ */
.main{
    margin-left:260px;
    flex:1;
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

/* TOPBAR */
.topbar{
    display:flex; justify-content:space-between; align-items:center;
    padding:20px 36px;
    border-bottom:1px solid var(--border);
    background:rgba(7,11,20,0.8);
    backdrop-filter:blur(12px);
    position:sticky; top:0; z-index:50;
}

.topbar-left h2{
    font-family:var(--ff-head);
    font-size:22px; font-weight:700;
}

.topbar-left p{ font-size:13px; color:var(--muted); margin-top:2px; }

.topbar-right{ display:flex; align-items:center; gap:14px; }

.tb-btn{
    display:flex; align-items:center; gap:8px;
    padding:8px 16px; border-radius:9px;
    font-size:13px; font-weight:500;
    text-decoration:none; transition:0.2s;
    font-family:var(--ff-body);
    border:none; cursor:pointer;
}

.tb-btn-ghost{
    background:rgba(255,255,255,0.05);
    color:var(--text);
    border:1px solid var(--border);
}

.tb-btn-ghost:hover{ background:rgba(255,255,255,0.09); }

.tb-btn-gold{
    background:var(--gold);
    color:#0a0e18;
    font-weight:700;
}

.tb-btn-gold:hover{ background:var(--gold2); }

.live-dot{
    width:8px; height:8px; border-radius:50%;
    background:var(--green);
    box-shadow:0 0 8px var(--green);
    animation:pulse 2s infinite;
}

@keyframes pulse{
    0%,100%{ opacity:1; }
    50%{ opacity:0.4; }
}

/* PAGE CONTENT */
.page-content{ padding:32px 36px; flex:1; }

/* ══ KPI GRID ══ */
.kpi-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-bottom:28px;
}

.kpi-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:18px;
    padding:22px 24px;
    position:relative;
    overflow:hidden;
    transition:0.25s;
}

.kpi-card:hover{
    border-color:rgba(255,255,255,0.12);
    transform:translateY(-3px);
    box-shadow:0 12px 30px rgba(0,0,0,0.3);
}

.kpi-card::before{
    content:'';
    position:absolute;
    top:0; left:0; right:0; height:2px;
}

.kpi-card.gold::before{ background:linear-gradient(90deg,var(--gold),var(--gold2)); }
.kpi-card.sky::before{  background:linear-gradient(90deg,var(--sky),#0ea5e9); }
.kpi-card.green::before{ background:linear-gradient(90deg,var(--green),#16a34a); }
.kpi-card.purple::before{ background:linear-gradient(90deg,var(--purple),#7c3aed); }
.kpi-card.amber::before{ background:linear-gradient(90deg,var(--amber),#d97706); }
.kpi-card.red::before{ background:linear-gradient(90deg,var(--red),#b91c1c); }

.kpi-top{
    display:flex; justify-content:space-between; align-items:flex-start;
    margin-bottom:16px;
}

.kpi-icon{
    width:42px; height:42px; border-radius:11px;
    display:flex; align-items:center; justify-content:center;
    font-size:17px; flex-shrink:0;
}

.kpi-card.gold .kpi-icon{ background:rgba(201,168,76,0.15); color:var(--gold); }
.kpi-card.sky  .kpi-icon{ background:rgba(56,189,248,0.12); color:var(--sky); }
.kpi-card.green .kpi-icon{ background:rgba(34,197,94,0.12); color:var(--green); }
.kpi-card.purple .kpi-icon{ background:rgba(168,85,247,0.12); color:var(--purple); }
.kpi-card.amber .kpi-icon{ background:rgba(245,158,11,0.12); color:var(--amber); }
.kpi-card.red .kpi-icon{ background:rgba(239,68,68,0.12); color:var(--red); }

.kpi-num{
    font-family:var(--ff-head);
    font-size:32px; font-weight:800; line-height:1;
    margin-bottom:4px;
}

.kpi-label{ font-size:12px; color:var(--muted); letter-spacing:0.5px; }

.kpi-badge{
    font-size:11px; font-weight:600;
    padding:3px 8px; border-radius:99px;
}

/* ══ SECTION GRID ══ */
.section-grid{
    display:grid;
    grid-template-columns:1fr 360px;
    gap:22px;
    margin-bottom:28px;
}

/* GLASS PANEL */
.panel{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:20px;
    overflow:hidden;
}

.panel-head{
    display:flex; justify-content:space-between; align-items:center;
    padding:20px 24px;
    border-bottom:1px solid var(--border);
}

.panel-title{
    font-family:var(--ff-head);
    font-size:16px; font-weight:700;
    display:flex; align-items:center; gap:10px;
}

.panel-title i{ color:var(--sky); font-size:14px; }

.panel-link{
    font-size:12px; color:var(--gold);
    text-decoration:none; transition:0.2s;
    display:flex; align-items:center; gap:5px;
}

.panel-link:hover{ color:var(--gold2); }

/* BOOKING TABLE */
.booking-table{ width:100%; border-collapse:collapse; }

.booking-table th{
    padding:10px 16px;
    font-size:10px; text-transform:uppercase; letter-spacing:1.5px;
    color:var(--muted); font-weight:600; text-align:left;
    background:rgba(255,255,255,0.02);
    border-bottom:1px solid var(--border);
}

.booking-table td{
    padding:13px 16px;
    font-size:13px;
    border-bottom:1px solid rgba(255,255,255,0.04);
}

.booking-table tr:last-child td{ border-bottom:none; }

.booking-table tr:hover td{ background:rgba(255,255,255,0.02); }

.status-pill{
    display:inline-block; padding:3px 10px;
    border-radius:99px; font-size:11px; font-weight:700;
    letter-spacing:0.3px;
}

.sp-confirmed{ background:rgba(34,197,94,0.12); color:#4ade80; }
.sp-pending{   background:rgba(245,158,11,0.12); color:#fbbf24; }
.sp-cancelled{ background:rgba(239,68,68,0.12);  color:#f87171; }
.sp-default{   background:rgba(255,255,255,0.06); color:#94a3b8; }

/* ROUTE CARDS */
.route-list{ padding:16px; display:flex; flex-direction:column; gap:10px; }

.route-item{
    display:flex; align-items:center; gap:14px;
    background:rgba(255,255,255,0.03);
    border:1px solid var(--border);
    border-radius:12px; padding:14px 16px;
    transition:0.2s;
}

.route-item:hover{ background:rgba(255,255,255,0.06); }

.route-rank{
    width:28px; height:28px; border-radius:8px;
    background:rgba(201,168,76,0.1);
    display:flex; align-items:center; justify-content:center;
    font-size:12px; font-weight:800; color:var(--gold);
    font-family:var(--ff-head); flex-shrink:0;
}

.route-cities{
    flex:1;
    font-size:14px; font-weight:600;
    display:flex; align-items:center; gap:8px;
}

.route-cities i{ color:var(--sky); font-size:11px; }

.route-count{
    font-size:12px; color:var(--muted);
}

.route-bar-wrap{
    width:60px; height:4px;
    background:rgba(255,255,255,0.06);
    border-radius:99px; overflow:hidden;
}

.route-bar{
    height:100%; border-radius:99px;
    background:linear-gradient(90deg,var(--sky),var(--purple));
}

/* ══ ACTION CARDS ══ */
.action-section{ margin-bottom:28px; }

.section-heading{
    font-family:var(--ff-head);
    font-size:18px; font-weight:700;
    margin-bottom:16px;
    display:flex; align-items:center; gap:10px;
    color:var(--text);
}

.section-heading i{ color:var(--gold); font-size:15px; }

.action-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:14px;
}

.ac{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:22px 18px;
    text-decoration:none;
    color:var(--text);
    transition:0.25s;
    position:relative;
    overflow:hidden;
    display:flex; flex-direction:column; gap:14px;
}

.ac::after{
    content:'';
    position:absolute;
    bottom:0; left:0; right:0; height:2px;
    background:var(--accent-color, var(--gold));
    transform:scaleX(0);
    transition:0.3s;
    transform-origin:left;
}

.ac:hover{ transform:translateY(-5px); box-shadow:0 16px 40px rgba(0,0,0,0.35); }
.ac:hover::after{ transform:scaleX(1); }
.ac:hover{ border-color:rgba(255,255,255,0.1); }

.ac-icon{
    width:44px; height:44px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:18px;
}

.ac-title{ font-family:var(--ff-head); font-size:15px; font-weight:700; }
.ac-desc{ font-size:12px; color:var(--muted); line-height:1.5; }

/* ══ STATUS ROW ══ */
.status-row{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin-bottom:28px;
}

.status-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:20px 22px;
    display:flex; align-items:center; gap:16px;
}

.status-icon{
    width:46px; height:46px; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:20px; flex-shrink:0;
}

.si-green{ background:rgba(34,197,94,0.12); color:var(--green); }
.si-amber{ background:rgba(245,158,11,0.12); color:var(--amber); }
.si-red{   background:rgba(239,68,68,0.12);  color:var(--red); }

.status-info .num{
    font-family:var(--ff-head);
    font-size:26px; font-weight:800; line-height:1;
}

.status-info .lbl{ font-size:12px; color:var(--muted); margin-top:3px; }

/* RESPONSIVE */
@media(max-width:1200px){
    .kpi-grid{ grid-template-columns:repeat(2,1fr); }
    .action-grid{ grid-template-columns:repeat(3,1fr); }
}

@media(max-width:900px){
    .sidebar{ transform:translateX(-100%); }
    .main{ margin-left:0; }
    .section-grid{ grid-template-columns:1fr; }
    .status-row{ grid-template-columns:1fr; }
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="sb-logo">
        <div class="sb-logo-icon"><i class="fa-solid fa-plane"></i></div>
        <div class="sb-logo-text">Sky<span>velle</span></div>
    </div>

    <div class="sb-section">Overview</div>
    <a href="admin_dashboard.php" class="sb-link active">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>

    <div class="sb-section">Operations</div>
    <a href="add_flight_details.php" class="sb-link">
        <i class="fa-solid fa-calendar-plus"></i> Add Flight
    </a>
    <a href="delete_flight_details.php" class="sb-link">
        <i class="fa-solid fa-trash"></i> Delete Flight
    </a>
    <a href="add_jet_details.php" class="sb-link">
        <i class="fa-solid fa-jet-fighter"></i> Add Aircraft
    </a>
    <a href="deactivate_jet_details.php" class="sb-link">
        <i class="fa-solid fa-ban"></i> Deactivate Aircraft
    </a>

    <div class="sb-section">Bookings</div>
    <a href="admin_view_booked_tickets.php" class="sb-link">
        <i class="fa-solid fa-ticket"></i> All Bookings
    </a>
    <a href="admin_view_customers.php" class="sb-link">
        <i class="fa-solid fa-users"></i> Customers
    </a>
    <a href="admin_payments.php" class="sb-link">
        <i class="fa-solid fa-credit-card"></i> Payments
    </a>

    <div class="sb-bottom">
        <div class="admin-pill">
            <div class="admin-av">A</div>
            <div>
                <div class="admin-name">Admin</div>
                <div class="admin-role">Super Administrator</div>
            </div>
        </div>
    </div>

</aside>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <h2>Admin Dashboard</h2>
            <p>
                <?= date('l, d F Y') ?> &nbsp;·&nbsp;
                <span style="color:var(--green);">
                    <span class="live-dot" style="display:inline-block;vertical-align:middle;margin-right:4px;"></span>
                    Live
                </span>
            </p>
        </div>
        <div class="topbar-right">
            <a href="admin_view_booked_tickets.php" class="tb-btn tb-btn-ghost">
                <i class="fa-solid fa-ticket"></i> Bookings
            </a>
            <a href="add_flight_details.php" class="tb-btn tb-btn-gold">
                <i class="fa-solid fa-plus"></i> Add Flight
            </a>
            <a href="logout_handler.php" class="tb-btn tb-btn-ghost" style="color:#f87171;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>

    <div class="page-content">

        <!-- KPI GRID -->
        <div class="kpi-grid">

            <div class="kpi-card gold">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-num">₹<?= number_format($total_revenue) ?></div>
                        <div class="kpi-label">Total Revenue</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                </div>
                <span class="kpi-badge" style="background:rgba(201,168,76,0.12);color:var(--gold);">
                    All time
                </span>
            </div>

            <div class="kpi-card sky">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-num"><?= number_format($total_bookings) ?></div>
                        <div class="kpi-label">Total Bookings</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-ticket"></i></div>
                </div>
                <span class="kpi-badge" style="background:rgba(56,189,248,0.1);color:var(--sky);">
                    +<?= $bookings_today ?> today
                </span>
            </div>

            <div class="kpi-card green">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-num"><?= number_format($total_customers) ?></div>
                        <div class="kpi-label">Registered Customers</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                </div>
                <span class="kpi-badge" style="background:rgba(34,197,94,0.1);color:var(--green);">
                    <?= $ff_members ?> FF members
                </span>
            </div>

            <div class="kpi-card purple">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-num"><?= number_format($active_flights) ?></div>
                        <div class="kpi-label">Active Flights</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-plane"></i></div>
                </div>
                <span class="kpi-badge" style="background:rgba(168,85,247,0.1);color:var(--purple);">
                    <?= $total_jets ?> aircraft
                </span>
            </div>

        </div>

        <!-- BOOKING STATUS ROW -->
        <div class="status-row">
            <div class="status-card">
                <div class="status-icon si-green"><i class="fa-solid fa-circle-check"></i></div>
                <div class="status-info">
                    <div class="num" style="color:var(--green);"><?= $confirmed ?></div>
                    <div class="lbl">Confirmed Bookings</div>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon si-amber"><i class="fa-solid fa-clock"></i></div>
                <div class="status-info">
                    <div class="num" style="color:var(--amber);"><?= $pending ?></div>
                    <div class="lbl">Pending Payments</div>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon si-red"><i class="fa-solid fa-ban"></i></div>
                <div class="status-info">
                    <div class="num" style="color:var(--red);"><?= $cancelled ?></div>
                    <div class="lbl">Cancelled Bookings</div>
                </div>
            </div>
        </div>

        <!-- RECENT BOOKINGS + TOP ROUTES -->
        <div class="section-grid">

            <!-- RECENT BOOKINGS -->
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Recent Bookings
                    </div>
                    <a href="admin_view_booked_tickets.php" class="panel-link">
                        View all <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>PNR</th>
                            <th>Customer</th>
                            <th>Flight</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $max_booking = max(1, $total_bookings);
                    while($row = mysqli_fetch_assoc($recent)):
                        $bs  = strtolower($row['booking_status']);
                        $bsc = 'sp-default';
                        if(str_contains($bs,'confirm')) $bsc = 'sp-confirmed';
                        elseif(str_contains($bs,'pend')) $bsc = 'sp-pending';
                        elseif(str_contains($bs,'canc')) $bsc = 'sp-cancelled';
                    ?>
                    <tr>
                        <td style="font-family:monospace;font-size:12px;color:var(--sky);">
                            <?= htmlspecialchars($row['pnr']) ?>
                        </td>
                        <td><?= htmlspecialchars($row['cust_name'] ?? '—') ?></td>
                        <td style="color:var(--muted);"><?= htmlspecialchars($row['flight_no']) ?></td>
                        <td style="color:var(--muted);font-size:12px;"><?= htmlspecialchars($row['journey_date']) ?></td>
                        <td><span class="status-pill <?= $bsc ?>"><?= htmlspecialchars($row['booking_status']) ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- TOP ROUTES -->
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <i class="fa-solid fa-route"></i>
                        Top Routes
                    </div>
                </div>
                <div class="route-list">
                <?php
                $route_rows = [];
                while($row = mysqli_fetch_assoc($routes)) $route_rows[] = $row;
                $max_route = max(1, !empty($route_rows) ? $route_rows[0]['cnt'] : 1);
                foreach($route_rows as $i => $row):
                ?>
                <div class="route-item">
                    <div class="route-rank"><?= $i+1 ?></div>
                    <div class="route-cities">
                        <?= htmlspecialchars(ucfirst($row['from_city'])) ?>
                        <i class="fa-solid fa-arrow-right"></i>
                        <?= htmlspecialchars(ucfirst($row['to_city'])) ?>
                    </div>
                    <div class="route-count"><?= $row['cnt'] ?> bookings</div>
                    <div class="route-bar-wrap">
                        <div class="route-bar" style="width:<?= round(($row['cnt']/$max_route)*100) ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($route_rows)): ?>
                <p style="color:var(--muted);text-align:center;padding:20px;font-size:13px;">No route data yet</p>
                <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->
        <div class="action-section">
            <div class="section-heading">
                <i class="fa-solid fa-bolt"></i> Quick Actions
            </div>
            <div class="action-grid">

                <a href="admin_view_booked_tickets.php" class="ac" style="--accent-color:var(--sky);">
                    <div class="ac-icon" style="background:rgba(56,189,248,0.1);color:var(--sky);">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div>
                        <div class="ac-title">All Bookings</div>
                        <div class="ac-desc">View and manage all passenger reservations</div>
                    </div>
                </a>

                <a href="add_flight_details.php" class="ac" style="--accent-color:var(--purple);">
                    <div class="ac-icon" style="background:rgba(168,85,247,0.1);color:var(--purple);">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <div class="ac-title">Add Flight</div>
                        <div class="ac-desc">Schedule new flight routes and timings</div>
                    </div>
                </a>

                <a href="delete_flight_details.php" class="ac" style="--accent-color:var(--red);">
                    <div class="ac-icon" style="background:rgba(239,68,68,0.1);color:var(--red);">
                        <i class="fa-solid fa-trash"></i>
                    </div>
                    <div>
                        <div class="ac-title">Delete Flight</div>
                        <div class="ac-desc">Remove cancelled or outdated schedules</div>
                    </div>
                </a>

                <a href="add_jet_details.php" class="ac" style="--accent-color:var(--green);">
                    <div class="ac-icon" style="background:rgba(34,197,94,0.1);color:var(--green);">
                        <i class="fa-solid fa-jet-fighter"></i>
                    </div>
                    <div>
                        <div class="ac-title">Add Aircraft</div>
                        <div class="ac-desc">Register new jets to the fleet</div>
                    </div>
                </a>

                <a href="deactivate_jet_details.php" class="ac" style="--accent-color:var(--amber);">
                    <div class="ac-icon" style="background:rgba(245,158,11,0.1);color:var(--amber);">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <div>
                        <div class="ac-title">Deactivate Jet</div>
                        <div class="ac-desc">Suspend aircraft from operations</div>
                    </div>
                </a>

            </div>
        </div>

    </div><!-- end page-content -->
</div><!-- end main -->

</body>
</html>
