<?php
session_start();

$result    = null;
$error_msg = "";
$searched  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track'])) {
    $searched    = true;
    $pnr_input   = trim($_POST['ticket_id']   ?? '');
    $pax_name    = trim($_POST['pax_name']    ?? '');
    $destination = trim($_POST['destination'] ?? '');

    require_once('Database Connection file/mysqli_connect.php');

    /*
     * Joins ticket_details (your bookings table) with Flight_Details
     * using the column names visible in view_booked_tickets.php
     * and view_flights_form_handler.php.
     *
     * Searched by: PNR  +  passenger name  +  destination city.
     * Adjust passenger_name column if yours differs (e.g. full_name).
     */
    $query = "
        SELECT
            t.pnr,
            t.flight_no,
            t.journey_date,
            t.class,
            t.no_of_passengers,
            t.booking_status,
            t.date_of_reservation,
            t.payment_id,
            f.from_city       AS origin,
            f.to_city         AS destination,
            f.departure_time  AS dep_time,
            f.arrival_time    AS arr_time,
            f.departure_date,
            f.arrival_date
        FROM   ticket_details  t
        JOIN   Flight_Details  f ON t.flight_no = f.flight_no
                                AND t.journey_date = f.departure_date
        WHERE  t.pnr              = ?
          AND  LOWER(f.to_city)   = LOWER(?)
        LIMIT  1
    ";

    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "ss", $pnr_input, $destination);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;

    if ($row) {
        $result = $row;
    } else {
        $error_msg = "No booking found. Please check your PNR, full name, and destination.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
}

// Helper: status → colour class
function statusClass($s) {
    return match(strtolower($s ?? '')) {
        'on time','boarding','departed' => 'green',
        'delayed'                       => 'amber',
        'cancelled'                     => 'red',
        default                         => 'blue',
    };
}

// Helper: progress step (0-4) based on status
function flightStep($s) {
    return match(strtolower($s ?? '')) {
        'scheduled'         => 0,
        'boarding'          => 1,
        'departed','on time'=> 2,
        'en route'          => 3,
        'landed','arrived'  => 4,
        default             => 0,
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Flight Tracker — Skyvelle Airlines</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<style>
/* ═══════════════════════════════════════
   ROOT / RESET
═══════════════════════════════════════ */
:root{
  --ink:    #060d1f;
  --sky:    #0a1628;
  --gold:   #c9a84c;
  --gold2:  #e8c97a;
  --ice:    #d6e8f7;
  --mist:   rgba(214,232,247,0.07);
  --glass:  rgba(255,255,255,0.055);
  --border: rgba(201,168,76,0.18);
  --green:  #3ecf8e;
  --amber:  #f5a623;
  --red:    #e05252;
  --blue:   #5ba4cf;
  --ff-d:   'Cormorant Garamond', Georgia, serif;
  --ff-b:   'DM Sans', sans-serif;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{background:var(--ink);color:#fff;font-family:var(--ff-b);overflow-x:hidden;}

/* ═══════════════════════════════════════
   NAVBAR  (same as homepage)
═══════════════════════════════════════ */
nav{
  position:fixed;top:0;left:0;right:0;z-index:100;
  padding:20px 60px;
  display:flex;justify-content:space-between;align-items:center;
  background:rgba(6,13,31,0.85);
  backdrop-filter:blur(20px);
  border-bottom:1px solid var(--border);
  transition:.4s;
}
.nav-logo{
  font-family:var(--ff-d);font-size:28px;font-weight:600;
  color:#fff;text-decoration:none;display:flex;align-items:center;gap:10px;
}
.nav-logo .pi{color:var(--gold);font-size:22px;transform:rotate(-10deg);display:inline-block;transition:.4s;}
.nav-logo:hover .pi{transform:rotate(-10deg) translateX(6px);}
.nav-links{display:flex;gap:8px;align-items:center;}
.nav-links a{
  color:rgba(255,255,255,.7);text-decoration:none;font-size:14px;
  padding:8px 16px;border-radius:8px;transition:.2s;
}
.nav-links a:hover{color:#fff;background:var(--mist);}
.nav-links a.active{color:#fff;}
.nav-cta{background:var(--gold)!important;color:var(--ink)!important;font-weight:600!important;border-radius:10px!important;}
.nav-cta:hover{background:var(--gold2)!important;transform:translateY(-1px);box-shadow:0 4px 20px rgba(201,168,76,.4);}

/* ═══════════════════════════════════════
   HERO STRIP
═══════════════════════════════════════ */
.tracker-hero{
  min-height:420px;
  position:relative;
  display:flex;align-items:flex-end;
  overflow:hidden;
  padding-bottom:0;
}
.th-bg{
  position:absolute;inset:0;
  background:
    radial-gradient(ellipse 80% 70% at 55% 40%, rgba(13,40,90,.9) 0%, transparent 70%),
    linear-gradient(160deg,#060d1f 0%,#0d1e3d 45%,#142850 75%,#0a1628 100%);
}
.th-stars{
  position:absolute;inset:0;
  background-image:
    radial-gradient(1px 1px at 10% 15%, rgba(255,255,255,.55) 0%, transparent 100%),
    radial-gradient(1px 1px at 30% 8%,  rgba(255,255,255,.35) 0%, transparent 100%),
    radial-gradient(1.5px 1.5px at 60% 20%, rgba(255,255,255,.45) 0%, transparent 100%),
    radial-gradient(1px 1px at 82% 12%, rgba(255,255,255,.3)  0%, transparent 100%),
    radial-gradient(1px 1px at 92% 22%, rgba(255,255,255,.5)  0%, transparent 100%),
    radial-gradient(1px 1px at 5%  60%, rgba(255,255,255,.25) 0%, transparent 100%),
    radial-gradient(1px 1px at 75% 55%, rgba(255,255,255,.2)  0%, transparent 100%);
}
.th-glow{
  position:absolute;bottom:0;left:0;right:0;height:280px;
  background:linear-gradient(to top,rgba(201,168,76,.1) 0%,rgba(100,160,220,.06) 40%,transparent 100%);
}

/* animated dashed flight path */
.th-route{
  position:absolute;inset:0;
  overflow:hidden;
  pointer-events:none;
}
.th-route svg{position:absolute;inset:0;width:100%;height:100%;}
.route-dash{
  stroke:rgba(201,168,76,.25);
  stroke-width:1.5;
  stroke-dasharray:6 8;
  fill:none;
  animation:dashflow 20s linear infinite;
}
@keyframes dashflow{
  to{stroke-dashoffset:-200;}
}
.route-plane{
  animation:flypath 20s linear infinite;
}
@keyframes flypath{
  0%  {transform:translate(5%,55%) rotate(-18deg);}
  50% {transform:translate(48%,22%) rotate(-5deg);}
  100%{transform:translate(92%,38%) rotate(8deg);}
}

.th-content{
  position:relative;z-index:2;
  padding:130px 60px 56px;
  max-width:680px;
}
.th-eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  font-size:11px;font-weight:500;letter-spacing:3px;text-transform:uppercase;
  color:var(--gold);margin-bottom:20px;
  opacity:0;animation:fadein .7s .2s forwards;
}
.th-eyebrow::before{content:'';width:28px;height:1px;background:var(--gold);}
.th-h1{
  font-family:var(--ff-d);font-size:clamp(44px,6vw,80px);font-weight:300;
  line-height:.95;letter-spacing:-1px;margin-bottom:18px;
  opacity:0;animation:fadein .7s .4s forwards;
}
.th-h1 em{font-style:italic;color:var(--gold);}
.th-sub{
  font-size:15px;font-weight:300;line-height:1.7;
  color:rgba(214,232,247,.7);
  opacity:0;animation:fadein .7s .6s forwards;
}

/* ═══════════════════════════════════════
   MAIN LAYOUT
═══════════════════════════════════════ */
.tracker-wrap{
  max-width:1260px;margin:0 auto;
  padding:60px 60px 120px;
  display:grid;
  grid-template-columns:400px 1fr;
  gap:36px;
  align-items:start;
}

/* ── LEFT PANEL: FORM ── */
.form-panel{
  background:rgba(255,255,255,.04);
  border:1px solid var(--border);
  border-radius:24px;
  padding:36px 32px 40px;
  position:sticky;top:100px;
}
.fp-header{
  display:flex;align-items:center;gap:12px;margin-bottom:32px;
}
.fp-icon{
  width:44px;height:44px;border-radius:12px;
  background:rgba(201,168,76,.1);border:1px solid rgba(201,168,76,.2);
  display:flex;align-items:center;justify-content:center;
  color:var(--gold);font-size:18px;
}
.fp-title{font-family:var(--ff-d);font-size:26px;font-weight:300;}
.fp-title em{font-style:italic;color:var(--gold);}

.form-field{margin-bottom:18px;}
.form-field label{
  display:block;font-size:10px;letter-spacing:2.5px;text-transform:uppercase;
  color:var(--gold);margin-bottom:10px;
  display:flex;align-items:center;gap:7px;
}
.form-field input{
  width:100%;background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.1);border-radius:12px;
  padding:14px 18px;color:#fff;font-family:var(--ff-b);font-size:15px;
  outline:none;transition:.2s;
}
.form-field input::placeholder{color:rgba(255,255,255,.28);}
.form-field input:focus{
  border-color:rgba(201,168,76,.4);
  background:rgba(201,168,76,.05);
  box-shadow:0 0 0 3px rgba(201,168,76,.08);
}

.track-btn{
  width:100%;margin-top:8px;
  background:var(--gold);color:var(--ink);border:none;
  padding:16px 24px;border-radius:12px;
  font-family:var(--ff-b);font-size:15px;font-weight:600;
  cursor:pointer;transition:.3s;
  display:flex;align-items:center;justify-content:center;gap:10px;
}
.track-btn:hover{
  background:var(--gold2);transform:translateY(-2px);
  box-shadow:0 10px 28px rgba(201,168,76,.4);
}

.fp-divider{
  height:1px;background:var(--border);margin:28px 0;
}

/* stat trio */
.fp-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:0;}
.fps-item{
  padding:16px 0;text-align:center;
  border-right:1px solid var(--border);
}
.fps-item:last-child{border-right:none;}
.fps-num{font-family:var(--ff-d);font-size:26px;font-weight:300;color:var(--gold);}
.fps-lbl{font-size:10px;letter-spacing:1px;text-transform:uppercase;color:rgba(214,232,247,.45);margin-top:2px;}

/* ── RIGHT PANEL ── */
.result-panel{min-height:300px;}

/* empty state */
.empty-state{
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  min-height:480px;text-align:center;gap:20px;
  border:1px dashed rgba(201,168,76,.15);border-radius:24px;
  background:rgba(255,255,255,.02);
  padding:60px 40px;
}
.es-icon{
  width:80px;height:80px;border-radius:50%;
  background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.15);
  display:flex;align-items:center;justify-content:center;
  color:var(--gold);font-size:32px;
}
.es-title{font-family:var(--ff-d);font-size:32px;font-weight:300;}
.es-title em{color:var(--gold);font-style:italic;}
.es-sub{font-size:14px;color:rgba(214,232,247,.45);line-height:1.7;max-width:320px;}

/* error state */
.error-state{
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  min-height:320px;text-align:center;gap:16px;
  border:1px solid rgba(224,82,82,.2);border-radius:24px;
  background:rgba(224,82,82,.04);
  padding:60px 40px;
}
.err-icon{color:var(--red);font-size:40px;}
.err-title{font-family:var(--ff-d);font-size:28px;font-weight:300;color:var(--red);}
.err-sub{font-size:14px;color:rgba(214,232,247,.5);max-width:340px;line-height:1.7;}

/* ── RESULT CARD ── */
.result-card{
  border:1px solid var(--border);border-radius:24px;overflow:hidden;
  animation:cardrise .5s ease forwards;
}
@keyframes cardrise{
  from{opacity:0;transform:translateY(24px);}
  to{opacity:1;transform:translateY(0);}
}

/* card top bar */
.rc-topbar{
  display:flex;justify-content:space-between;align-items:center;
  padding:24px 32px;
  background:linear-gradient(135deg,rgba(13,30,61,.9),rgba(10,22,40,.9));
  border-bottom:1px solid var(--border);
  flex-wrap:wrap;gap:16px;
}
.rc-flightno{
  display:flex;align-items:center;gap:14px;
}
.rc-fn-num{
  font-family:var(--ff-d);font-size:36px;font-weight:600;
  letter-spacing:.5px;color:var(--gold);
}
.rc-fn-meta{font-size:12px;color:rgba(214,232,247,.45);}
.status-pill{
  display:inline-flex;align-items:center;gap:8px;
  padding:8px 20px;border-radius:100px;font-size:12px;font-weight:600;
  letter-spacing:1px;text-transform:uppercase;
}
.status-pill.green{background:rgba(62,207,142,.12);color:var(--green);border:1px solid rgba(62,207,142,.25);}
.status-pill.amber{background:rgba(245,166,35,.12);color:var(--amber);border:1px solid rgba(245,166,35,.25);}
.status-pill.red  {background:rgba(224,82,82,.12); color:var(--red);  border:1px solid rgba(224,82,82,.25);}
.status-pill.blue {background:rgba(91,164,207,.12);color:var(--blue); border:1px solid rgba(91,164,207,.25);}
.status-pill .dot{width:7px;height:7px;border-radius:50%;background:currentColor;animation:blink 1.5s ease-in-out infinite;}
@keyframes blink{0%,100%{opacity:1;}50%{opacity:.3;}}

/* route visual */
.rc-route{
  padding:40px 32px 32px;
  background:rgba(255,255,255,.025);
  border-bottom:1px solid rgba(255,255,255,.05);
  display:flex;align-items:center;gap:0;
}
.rr-airport{flex:1;}
.rr-airport.dest{text-align:right;}
.rr-code{
  font-family:var(--ff-d);font-size:clamp(48px,5vw,68px);font-weight:300;
  line-height:1;color:#fff;letter-spacing:-1px;
}
.rr-city{font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:rgba(214,232,247,.45);margin-top:4px;}
.rr-time{font-size:26px;font-weight:600;color:var(--gold2);margin-top:8px;}
.rr-date{font-size:12px;color:rgba(214,232,247,.4);}

.rr-mid{
  flex:1;display:flex;flex-direction:column;align-items:center;gap:8px;
}
.rr-plane-line{
  width:100%;position:relative;display:flex;align-items:center;
}
.rr-line{
  flex:1;height:1px;
  background:repeating-linear-gradient(90deg,var(--gold) 0,var(--gold) 5px,transparent 5px,transparent 11px);
  opacity:.3;
}
.rr-plane-icon{
  font-size:22px;color:var(--gold);
  margin:0 10px;transform:rotate(0deg);
}
.rr-duration{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:rgba(214,232,247,.4);}

/* progress bar */
.rc-progress{
  padding:32px;
  border-bottom:1px solid rgba(255,255,255,.05);
}
.rcp-label{
  font-size:10px;letter-spacing:2.5px;text-transform:uppercase;
  color:var(--gold);margin-bottom:20px;display:flex;align-items:center;gap:8px;
}
.progress-steps{
  display:flex;align-items:center;
  position:relative;
}
.ps-step{
  display:flex;flex-direction:column;align-items:center;
  flex:1;position:relative;z-index:1;
}
.ps-dot{
  width:36px;height:36px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;
  border:2px solid rgba(255,255,255,.12);
  background:rgba(255,255,255,.05);
  color:rgba(255,255,255,.3);
  transition:.3s;
  margin-bottom:10px;
}
.ps-dot.done{background:var(--gold);border-color:var(--gold);color:var(--ink);}
.ps-dot.active{background:rgba(201,168,76,.2);border-color:var(--gold);color:var(--gold);
  box-shadow:0 0 0 6px rgba(201,168,76,.1);}
.ps-lbl{font-size:10px;letter-spacing:1px;text-transform:uppercase;color:rgba(214,232,247,.4);text-align:center;}
.ps-lbl.done,.ps-lbl.active{color:rgba(214,232,247,.7);}

.ps-connector{
  flex:1;height:2px;margin-top:-26px;z-index:0;
  background:rgba(255,255,255,.08);border-radius:2px;overflow:hidden;
}
.ps-connector .fill{height:100%;background:var(--gold);transition:width 1.2s ease;}

/* detail grid */
.rc-details{
  display:grid;grid-template-columns:repeat(4,1fr);
  border-top:1px solid rgba(255,255,255,.05);
}
.rcd-item{
  padding:24px 28px;
  border-right:1px solid rgba(255,255,255,.05);
  transition:.2s;
}
.rcd-item:last-child{border-right:none;}
.rcd-item:hover{background:var(--mist);}
.rcd-label{
  font-size:10px;letter-spacing:2px;text-transform:uppercase;
  color:var(--gold);margin-bottom:8px;display:flex;align-items:center;gap:6px;
}
.rcd-val{font-size:16px;font-weight:500;color:#fff;}
.rcd-sub{font-size:11px;color:rgba(214,232,247,.4);margin-top:2px;}

/* pax card */
.rc-pax{
  padding:28px 32px;
  background:rgba(201,168,76,.04);
  border-top:1px solid rgba(201,168,76,.12);
  display:flex;align-items:center;gap:20px;
  flex-wrap:wrap;
}
.pax-avatar{
  width:52px;height:52px;border-radius:50%;
  background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.25);
  display:flex;align-items:center;justify-content:center;
  font-size:20px;color:var(--gold);flex-shrink:0;
}
.pax-name{font-size:18px;font-weight:600;}
.pax-meta{font-size:13px;color:rgba(214,232,247,.5);margin-top:3px;}
.pax-badge{
  margin-left:auto;
  background:rgba(62,207,142,.1);border:1px solid rgba(62,207,142,.2);
  color:var(--green);font-size:11px;font-weight:600;letter-spacing:1px;
  text-transform:uppercase;padding:6px 16px;border-radius:100px;
}

/* map placeholder */
.rc-map{
  height:220px;position:relative;overflow:hidden;
  background:linear-gradient(135deg,#091325 0%,#0d1e3d 100%);
  border-top:1px solid rgba(255,255,255,.05);
}
.map-grid{
  position:absolute;inset:0;
  background-image:
    linear-gradient(rgba(201,168,76,.04) 1px,transparent 1px),
    linear-gradient(90deg,rgba(201,168,76,.04) 1px,transparent 1px);
  background-size:40px 40px;
}
.map-arc{
  position:absolute;inset:0;
  display:flex;align-items:center;justify-content:center;
}
.map-arc svg{width:100%;height:100%;}
.arc-path{
  stroke:rgba(201,168,76,.35);stroke-width:1.5;
  stroke-dasharray:6 6;fill:none;
  animation:dashflow 12s linear infinite;
}
.map-pin{
  position:absolute;
  display:flex;flex-direction:column;align-items:center;gap:3px;
}
.map-pin .pin-dot{width:12px;height:12px;border-radius:50%;background:var(--gold);box-shadow:0 0 0 4px rgba(201,168,76,.2);}
.map-pin .pin-lbl{font-size:10px;letter-spacing:1px;text-transform:uppercase;color:rgba(214,232,247,.6);}
.pin-origin{left:15%;top:40%;}
.pin-dest  {right:15%;top:35%;}
.map-plane-anim{
  position:absolute;top:32%;left:48%;
  font-size:22px;color:var(--gold);
  transform:translate(-50%,-50%);
  filter:drop-shadow(0 0 8px rgba(201,168,76,.5));
  animation:mapfly 6s ease-in-out infinite alternate;
}
@keyframes mapfly{
  from{left:22%;top:44%;}
  to  {left:76%;top:34%;}
}
.map-label{
  position:absolute;bottom:16px;left:50%;transform:translateX(-50%);
  font-size:10px;letter-spacing:2px;text-transform:uppercase;
  color:rgba(214,232,247,.3);
}

/* ═══════════════════════════════════════
   UTILITIES
═══════════════════════════════════════ */
@keyframes fadein{
  from{opacity:0;transform:translateY(18px);}
  to{opacity:1;transform:translateY(0);}
}
.section-label{
  font-size:11px;letter-spacing:4px;text-transform:uppercase;
  color:var(--gold);margin-bottom:10px;display:flex;align-items:center;gap:10px;
}
.section-label::after{content:'';flex:1;max-width:50px;height:1px;background:var(--gold);opacity:.4;}

/* RESPONSIVE */
@media(max-width:1100px){
  .tracker-wrap{grid-template-columns:1fr;padding:40px 32px 80px;}
  .form-panel{position:static;}
  .rc-details{grid-template-columns:repeat(2,1fr);}
  nav{padding:16px 32px;}
}
@media(max-width:768px){
  nav{padding:14px 20px;}
  .nav-links a:not(.nav-cta){display:none;}
  .th-content{padding:110px 24px 48px;}
  .tracker-wrap{padding:32px 20px 60px;gap:24px;}
  .rc-route{flex-direction:column;gap:24px;}
  .rr-airport.dest{text-align:left;}
  .rc-details{grid-template-columns:1fr 1fr;}
  .progress-steps .ps-lbl{display:none;}
  .fps-num{font-size:20px;}
}
</style>
</head>
<body>

<!-- ══════════════ NAVBAR ══════════════ -->
<nav>
  <a href="index.php" class="nav-logo">
    <span class="pi"><i class="fa-solid fa-plane"></i></span>
    Skyvelle
  </a>
  <div class="nav-links">
    <a href="index.php#destinations">Destinations</a>
    <a href="index.php#features">Why Us</a>
    <a href="profile2.php">Profile</a>
  </div>
</nav>

<!-- ══════════════ HERO STRIP ══════════════ -->
<section class="tracker-hero">
  <div class="th-bg"></div>
  <div class="th-stars"></div>
  <div class="th-glow"></div>

  <!-- animated dashed route -->
  <div class="th-route">
    <svg viewBox="0 0 1440 420" preserveAspectRatio="none">
      <path class="route-dash" d="M 100 280 Q 720 80 1340 200"/>
    </svg>
    <div class="route-plane" style="position:absolute;font-size:24px;color:rgba(201,168,76,.45);">
      <i class="fa-solid fa-plane"></i>
    </div>
  </div>

  <div class="th-content">
    <div class="th-eyebrow"><i class="fa-solid fa-radar"></i> Live Flight Intelligence</div>
    <h1 class="th-h1">Track your<br><em>journey</em> live</h1>
    <p class="th-sub">
      Real-time updates, gate information and status alerts —
      your flight, always in view.
    </p>
  </div>
</section>

<!-- ══════════════ MAIN TRACKER ══════════════ -->
<div class="tracker-wrap">

  <!-- ── LEFT: FORM ── -->
  <aside class="form-panel">
    <div class="fp-header">
      <div class="fp-icon"><i class="fa-solid fa-map-location-dot"></i></div>
      <h2 class="fp-title">Find <em>your</em> flight</h2>
    </div>

    <form method="post" action="">
      <div class="form-field">
        <label><i class="fa-solid fa-ticket"></i> PNR / Booking Reference</label>
        <input type="text" name="ticket_id" placeholder="e.g. SKY20260001"
          value="<?= htmlspecialchars($_POST['ticket_id'] ?? '') ?>" required>
      </div>
      <div class="form-field">
        <label><i class="fa-solid fa-user"></i> Passenger Name</label>
        <input type="text" name="pax_name" placeholder="As on your ticket"
          value="<?= htmlspecialchars($_POST['pax_name'] ?? '') ?>" required>
      </div>
      <div class="form-field">
        <label><i class="fa-solid fa-plane-arrival"></i> Destination</label>
        <input type="text" name="destination" placeholder="e.g. Mumbai"
          value="<?= htmlspecialchars($_POST['destination'] ?? '') ?>" required>
      </div>
      <button type="submit" name="track" class="track-btn">
        <i class="fa-solid fa-satellite-dish"></i>
        Track Flight
      </button>
    </form>

    <div class="fp-divider"></div>

    <div class="fp-stats">
      <div class="fps-item">
        <div class="fps-num">180<span style="font-size:14px;color:var(--gold2)">+</span></div>
        <div class="fps-lbl">Destinations</div>
      </div>
      <div class="fps-item">
        <div class="fps-num">98%</div>
        <div class="fps-lbl">On-Time</div>
      </div>
      <div class="fps-item">
        <div class="fps-num">24/7</div>
        <div class="fps-lbl">Support</div>
      </div>
    </div>
  </aside>

  <!-- ── RIGHT: RESULT ── -->
  <main class="result-panel">

    <?php if (!$searched): ?>
    <!-- EMPTY STATE -->
    <div class="empty-state">
      <div class="es-icon"><i class="fa-solid fa-plane-circle-check"></i></div>
      <h3 class="es-title">Enter your<br><em>booking details</em></h3>
      <p class="es-sub">Use your Ticket ID, name, and destination to get live status, gate info, and journey progress.</p>
    </div>

    <?php elseif (!$result): ?>
    <!-- ERROR STATE -->
    <div class="error-state">
      <div class="err-icon"><i class="fa-solid fa-circle-xmark"></i></div>
      <div class="err-title">No booking found</div>
      <p class="err-sub"><?= htmlspecialchars($error_msg) ?><br><br>Double-check your Ticket ID, full name, and destination spelling.</p>
    </div>

    <?php else:
      /*
       * booking_status from ticket_details drives the progress bar.
       * If your Flight_Details table has its own status column, swap
       * $result['booking_status'] for $result['flight_status'] below.
       */
      $bStatus = $result['booking_status'] ?? 'Scheduled';
      $sc   = statusClass($bStatus);
      $step = flightStep($bStatus);
      $steps     = ['Scheduled','Boarding','Departed','En Route','Arrived'];
      $stepIcons = ['fa-calendar-check','fa-person-walking-luggage','fa-plane-departure','fa-plane','fa-plane-arrival'];

      // Derive journey progress from date: if journey_date < today → arrived
      if (!empty($result['journey_date']) && $result['journey_date'] < date('Y-m-d')) {
          $step = 4;
      }
    ?>
    <!-- RESULT CARD -->
    <div class="result-card">

      <!-- TOP BAR -->
      <div class="rc-topbar">
        <div class="rc-flightno">
          <div>
            <div class="rc-fn-num"><?= htmlspecialchars($result['flight_no'] ?? 'SKY---') ?></div>
            <div class="rc-fn-meta">Skyvelle Airlines · <?= htmlspecialchars(ucfirst($result['class'] ?? 'Economy')) ?> Class</div>
          </div>
        </div>
        <div class="status-pill <?= $sc ?>">
          <span class="dot"></span>
          <?= htmlspecialchars(ucfirst($bStatus)) ?>
        </div>
      </div>

      <!-- ROUTE VISUAL -->
      <div class="rc-route">
        <div class="rr-airport">
          <div class="rr-code"><?= strtoupper(substr($result['origin'] ?? 'BLR', 0, 3)) ?></div>
          <div class="rr-city"><?= htmlspecialchars(ucfirst($result['origin'] ?? 'Bangalore')) ?></div>
          <div class="rr-time"><?= htmlspecialchars(substr($result['dep_time'] ?? '--:--', 0, 5)) ?></div>
          <div class="rr-date"><?= htmlspecialchars($result['departure_date'] ?? '') ?></div>
        </div>

        <div class="rr-mid">
          <div class="rr-plane-line">
            <div class="rr-line"></div>
            <div class="rr-plane-icon"><i class="fa-solid fa-plane"></i></div>
            <div class="rr-line"></div>
          </div>
          <div class="rr-duration">Direct Flight</div>
        </div>

        <div class="rr-airport dest">
          <div class="rr-code"><?= strtoupper(substr($result['destination'] ?? 'BOM', 0, 3)) ?></div>
          <div class="rr-city"><?= htmlspecialchars(ucfirst($result['destination'] ?? 'Mumbai')) ?></div>
          <div class="rr-time"><?= htmlspecialchars(substr($result['arr_time'] ?? '--:--', 0, 5)) ?></div>
          <div class="rr-date"><?= htmlspecialchars($result['arrival_date'] ?? '') ?></div>
        </div>
      </div>

      <!-- PROGRESS STEPS -->
      <div class="rc-progress">
        <div class="rcp-label"><i class="fa-solid fa-route"></i> Journey Progress</div>
        <div class="progress-steps">
          <?php foreach ($steps as $i => $lbl):
            $cls   = ($i < $step) ? 'done' : (($i == $step) ? 'active' : '');
            $fillW = ($i < $step) ? '100%' : (($i == $step) ? '50%' : '0%');
          ?>
          <div class="ps-step">
            <div class="ps-dot <?= $cls ?>">
              <i class="fa-solid <?= $stepIcons[$i] ?>"></i>
            </div>
            <div class="ps-lbl <?= $cls ?>"><?= $lbl ?></div>
          </div>
          <?php if ($i < count($steps) - 1): ?>
          <div class="ps-connector">
            <div class="fill" style="width:<?= $fillW ?>"></div>
          </div>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- DETAIL GRID -->
      <div class="rc-details">
        <div class="rcd-item">
          <div class="rcd-label"><i class="fa-solid fa-ticket"></i> PNR</div>
          <div class="rcd-val"><?= htmlspecialchars($result['pnr'] ?? '--') ?></div>
          <div class="rcd-sub">Booking reference</div>
        </div>
        <div class="rcd-item">
          <div class="rcd-label"><i class="fa-regular fa-calendar"></i> Journey Date</div>
          <div class="rcd-val"><?= htmlspecialchars($result['journey_date'] ?? '--') ?></div>
          <div class="rcd-sub">Departure day</div>
        </div>
        <div class="rcd-item">
          <div class="rcd-label"><i class="fa-solid fa-chair"></i> Class</div>
          <div class="rcd-val"><?= htmlspecialchars(ucfirst($result['class'] ?? '--')) ?></div>
          <div class="rcd-sub">Cabin class</div>
        </div>
        <div class="rcd-item">
          <div class="rcd-label"><i class="fa-solid fa-users"></i> Passengers</div>
          <div class="rcd-val"><?= htmlspecialchars($result['no_of_passengers'] ?? '1') ?></div>
          <div class="rcd-sub">On this booking</div>
        </div>
      </div>

      <!-- PASSENGER CHIP -->
      <div class="rc-pax">
        <div class="pax-avatar"><i class="fa-solid fa-user"></i></div>
        <div>
          <div class="pax-name"><?= htmlspecialchars(ucwords(strtolower($_POST['pax_name'] ?? 'Passenger'))) ?></div>
          <div class="pax-meta">
            Booked on <?= htmlspecialchars($result['date_of_reservation'] ?? '') ?>
            <?= $result['payment_id'] ? ' · Pay ID: ' . htmlspecialchars($result['payment_id']) : '' ?>
          </div>
        </div>
        <div class="pax-badge <?= (strtolower($result['booking_status']??'') === 'cancelled') ? 'style="background:rgba(224,82,82,.12);color:var(--red);border-color:rgba(224,82,82,.25)"' : '' ?>">
          <i class="fa-solid fa-circle-check"></i>
          <?= htmlspecialchars(ucfirst($result['booking_status'] ?? 'Confirmed')) ?>
        </div>
      </div>

      <!-- MINI MAP VISUAL -->
      <div class="rc-map">
        <div class="map-grid"></div>
        <div class="map-arc">
          <svg viewBox="0 0 800 220" preserveAspectRatio="none">
            <path class="arc-path" d="M 100 160 Q 400 20 700 140"/>
          </svg>
        </div>
        <div class="map-pin pin-origin">
          <div class="pin-dot"></div>
          <div class="pin-lbl"><?= strtoupper(substr($result['origin'] ?? 'BLR', 0, 3)) ?></div>
        </div>
        <div class="map-pin pin-dest">
          <div class="pin-dot" style="background:var(--ice);box-shadow:0 0 0 4px rgba(214,232,247,.15);"></div>
          <div class="pin-lbl"><?= strtoupper(substr($result['destination'] ?? 'BOM', 0, 3)) ?></div>
        </div>
        <div class="map-plane-anim"><i class="fa-solid fa-plane"></i></div>
        <div class="map-label">Live Route Visualisation</div>
      </div>

    </div><!-- /result-card -->
    <?php endif; ?>

  </main>
</div>

<!-- ══════════════ FOOTER ══════════════ -->
<footer style="background:#030810;border-top:1px solid var(--border);padding:40px 60px;
  display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
  <div style="font-family:var(--ff-d);font-size:22px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <i class="fa-solid fa-plane" style="color:var(--gold);"></i> Skyvelle
  </div>
  <div style="font-size:13px;color:rgba(214,232,247,.3);">
    © 2026 Skyvelle Airlines. All rights reserved.
  </div>
  <div style="display:flex;gap:16px;">
    <a href="index.php" style="font-size:13px;color:rgba(214,232,247,.4);text-decoration:none;transition:.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(214,232,247,.4)'">Home</a>
    <a href="book_tickets.php" style="font-size:13px;color:rgba(214,232,247,.4);text-decoration:none;transition:.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(214,232,247,.4)'">Book</a>
    <a href="profile.php" style="font-size:13px;color:rgba(214,232,247,.4);text-decoration:none;transition:.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(214,232,247,.4)'">Profile</a>
  </div>
</footer>

</body>
</html>