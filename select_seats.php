<?php
session_start();

if(!isset($_SESSION['userid'])){
    header("Location: login.php");
    exit();
}

/* ── required session vars from booking flow ── */
$pnr        = $_SESSION['pnr']        ?? '';
$flight_no  = $_SESSION['flight_no']  ?? '';
$no_of_pass = $_SESSION['no_of_pass'] ?? 1;
$class      = strtolower($_SESSION['class'] ?? 'economy');

require_once('Database Connection file/mysqli_connect.php');

/* ── get jet_id and capacity from flight ── */
$q = "SELECT f.jet_id, j.total_capacity, j.jet_type,
             f.seats_economy, f.seats_business
      FROM flight_details f
      LEFT JOIN jet_details j ON j.jet_id = f.jet_id
      WHERE f.flight_no = ?";
$st = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($st, "s", $flight_no);
mysqli_stmt_execute($st);
$res = mysqli_stmt_get_result($st);
$jet = mysqli_fetch_assoc($res);
mysqli_stmt_close($st);

$total_capacity = $jet['total_capacity'] ?? 120;
$jet_type       = $jet['jet_type']       ?? 'Standard';

/* seats: front 20% business, rest economy */
$business_rows_count = max(2, (int)round($total_capacity * 0.20 / 6));
$economy_rows_count  = (int)ceil(($total_capacity - $business_rows_count * 6) / 6);
$cols = ['A','B','C','D','E','F'];

/* ── already taken seats for this flight ── */
$tq = "SELECT p.seat_no
       FROM passengers p
       JOIN ticket_details t ON t.pnr = p.pnr
       WHERE t.flight_no = ? AND p.seat_no IS NOT NULL AND p.seat_no != ''";
$tst = mysqli_prepare($dbc, $tq);
mysqli_stmt_bind_param($tst, "s", $flight_no);
mysqli_stmt_execute($tst);
$tres = mysqli_stmt_get_result($tst);
$taken = [];
while($row = mysqli_fetch_assoc($tres)) $taken[] = $row['seat_no'];
mysqli_stmt_close($tst);

mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Seats — Skyvelle</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }

body{
    background:
    linear-gradient(rgba(3,8,25,0.93),rgba(3,8,25,0.96)),
    url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
    background-size:cover; background-position:center; background-attachment:fixed;
    color:white; min-height:100vh; padding:30px;
}

.navbar{
    display:flex; justify-content:space-between; align-items:center;
    padding:16px 28px; background:rgba(255,255,255,0.06);
    backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.08);
    border-radius:20px; margin-bottom:28px;
}
.logo{ font-size:26px; font-weight:700; }
.nav-links{ display:flex; gap:12px; }
.nav-links a{
    text-decoration:none; color:white; padding:10px 16px;
    border-radius:10px; transition:0.3s; font-size:14px;
}
.nav-links a:hover{ background:linear-gradient(135deg,#4361ee,#7209b7); }

/* PAGE LAYOUT */
.page-layout{
    display:grid;
    grid-template-columns:1fr 340px;
    gap:28px;
    max-width:1100px;
    margin:0 auto;
}

/* PLANE CARD */
.plane-card{
    background:rgba(255,255,255,0.07);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.09);
    border-radius:26px;
    padding:30px;
}

.card-title{
    font-size:18px; font-weight:600; margin-bottom:22px;
    display:flex; align-items:center; gap:10px; color:#dbeafe;
}
.card-title i{ color:#4cc9f0; }

/* PLANE SHAPE */
.plane-wrap{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:0;
}

.plane-nose{
    width:90px; height:50px;
    background:linear-gradient(135deg,#334155,#1e293b);
    border-radius:50% 50% 0 0 / 100% 100% 0 0;
    border:1px solid rgba(255,255,255,0.1);
    display:flex; align-items:center; justify-content:center;
    font-size:20px;
}

.plane-body{
    background:rgba(255,255,255,0.04);
    border-left:1px solid rgba(255,255,255,0.09);
    border-right:1px solid rgba(255,255,255,0.09);
    padding:10px 20px;
    width:100%;
    max-width:380px;
}

.plane-tail{
    width:100%;
    max-width:380px;
    height:30px;
    background:linear-gradient(135deg,#334155,#1e293b);
    border-radius:0 0 40% 40%;
    border:1px solid rgba(255,255,255,0.1);
}

/* SECTION LABEL */
.section-label-row{
    display:flex;
    align-items:center;
    gap:10px;
    padding:8px 0;
    margin:6px 0;
}
.section-label-badge{
    font-size:11px;
    font-weight:700;
    padding:4px 12px;
    border-radius:99px;
    letter-spacing:0.5px;
}
.badge-business{ background:linear-gradient(135deg,#f4b183,#d97706); color:#1a0500; }
.badge-economy{  background:linear-gradient(135deg,#4cc9f0,#4361ee); color:white; }

.section-divider{
    height:1px;
    background:rgba(255,255,255,0.1);
    margin:8px 0;
    flex:1;
}

/* SEAT ROW */
.seat-row{
    display:flex;
    align-items:center;
    gap:6px;
    margin-bottom:6px;
    justify-content:center;
}

.row-num{
    font-size:11px;
    color:#475569;
    width:22px;
    text-align:right;
    flex-shrink:0;
}

.aisle{ width:18px; flex-shrink:0; }

/* SEAT BUTTON */
.seat{
    width:36px; height:36px;
    border-radius:8px 8px 4px 4px;
    border:none;
    font-size:11px;
    font-weight:600;
    cursor:pointer;
    transition:0.15s;
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
}

.seat::before{
    content:'';
    position:absolute;
    top:-5px; left:4px; right:4px;
    height:5px;
    border-radius:4px 4px 0 0;
    background:inherit;
    filter:brightness(0.8);
}

.seat-available{
    background:#1e3a5f;
    color:#7dd3fc;
    border:1px solid #2563eb44;
}
.seat-available:hover{
    background:#2563eb;
    color:white;
    transform:scale(1.1);
    box-shadow:0 4px 12px rgba(37,99,235,0.5);
}

.seat-business-avail{
    background:#3b1f00;
    color:#fbbf24;
    border:1px solid #d9770644;
}
.seat-business-avail:hover{
    background:#d97706;
    color:white;
    transform:scale(1.1);
    box-shadow:0 4px 12px rgba(217,119,6,0.5);
}

.seat-taken{
    background:#3b1219;
    color:#f87171;
    cursor:not-allowed;
    border:1px solid #ef444433;
    opacity:0.7;
}

.seat-locked{
    background:rgba(255,255,255,0.03);
    color:rgba(255,255,255,0.15);
    cursor:not-allowed;
    border:1px solid rgba(255,255,255,0.06);
    opacity:0.35;
}

.seat-locked::before{ background:rgba(255,255,255,0.03); }

.seat-selected{
    background:linear-gradient(135deg,#4361ee,#7209b7) !important;
    color:white !important;
    border:none !important;
    transform:scale(1.1);
    box-shadow:0 4px 15px rgba(67,97,238,0.6);
}

/* LEGEND */
.legend{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    margin-top:20px;
    padding-top:18px;
    border-top:1px solid rgba(255,255,255,0.08);
    justify-content:center;
}
.legend-item{
    display:flex; align-items:center; gap:7px; font-size:12px; color:#94a3b8;
}
.legend-dot{
    width:20px; height:20px; border-radius:5px;
}

/* RIGHT PANEL */
.right-panel{ display:flex; flex-direction:column; gap:20px; }

.glass-card{
    background:rgba(255,255,255,0.07);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.09);
    border-radius:22px;
    padding:24px;
}

/* FLIGHT INFO */
.flight-info-row{
    display:flex; align-items:center; gap:10px;
    padding:10px 0;
    border-bottom:1px solid rgba(255,255,255,0.07);
    font-size:14px;
}
.flight-info-row:last-child{ border-bottom:none; }
.flight-info-row i{ color:#4cc9f0; width:18px; }
.fi-label{ color:#94a3b8; margin-right:6px; }

/* SELECTED SEATS LIST */
.selected-list{ display:flex; flex-direction:column; gap:10px; }

.selected-item{
    display:flex; align-items:center; justify-content:space-between;
    background:rgba(67,97,238,0.15);
    border:1px solid rgba(67,97,238,0.3);
    border-radius:12px;
    padding:10px 14px;
    font-size:14px;
}

.selected-item .pass-label{ color:#93c5fd; font-size:12px; }
.selected-item .seat-label{ font-weight:700; font-size:16px; color:#4cc9f0; }

.no-seat-yet{
    color:#475569;
    font-size:13px;
    text-align:center;
    padding:16px 0;
}

/* CONFIRM BUTTON */
.confirm-btn{
    width:100%;
    padding:16px;
    border:none;
    border-radius:16px;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white;
    font-size:16px;
    font-weight:600;
    font-family:'Poppins',sans-serif;
    cursor:pointer;
    transition:0.3s;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
}
.confirm-btn:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(67,97,238,0.45);
}
.confirm-btn:disabled{
    opacity:0.4;
    cursor:not-allowed;
    transform:none;
}

.progress-info{
    text-align:center;
    font-size:13px;
    color:#64748b;
    margin-top:8px;
}
.progress-info span{ color:#4cc9f0; font-weight:600; }

/* RESPONSIVE */
@media(max-width:900px){
    .page-layout{ grid-template-columns:1fr; }
    .navbar{ flex-direction:column; gap:12px; }
}
</style>
</head>
<body>

<div class="navbar">
    <div class="logo">✈ Skyvelle</div>
    <div class="nav-links">
        <a href="customer_homepage.php"><i class="fa-solid fa-house"></i> Home</a>
        <a href="logout_handler.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</div>

<div class="page-layout">

    <!-- LEFT: SEAT MAP -->
    <div class="plane-card">
        <div class="card-title">
            <i class="fa-solid fa-plane-up"></i>
            Select Your Seats &nbsp;
            <span style="font-size:13px;color:#94a3b8;font-weight:400;">
                — <?= $no_of_pass ?> seat(s) needed
            </span>
        </div>
        <div style="display:inline-flex;align-items:center;gap:8px;
             background:<?= $class==='business' ? 'rgba(217,119,6,0.15)' : 'rgba(67,97,238,0.15)' ?>;
             border:1px solid <?= $class==='business' ? 'rgba(217,119,6,0.35)' : 'rgba(67,97,238,0.35)' ?>;
             border-radius:10px; padding:8px 16px; font-size:13px; margin-bottom:20px;">
            <i class="fa-solid <?= $class==='business' ? 'fa-crown' : 'fa-person-seat' ?>"
               style="color:<?= $class==='business' ? '#fbbf24' : '#4cc9f0' ?>;"></i>
            You are booking <strong style="margin:0 4px;"><?= ucfirst($class) ?> Class</strong>
            — only <?= ucfirst($class) ?> seats are selectable
        </div>

        <!-- COLUMN HEADERS -->
        <div style="display:flex;justify-content:center;gap:6px;margin-bottom:8px;padding:0 20px;">
            <div style="width:22px;"></div>
            <?php foreach(['A','B','C'] as $c): ?>
            <div style="width:36px;text-align:center;font-size:11px;color:#64748b;font-weight:600;"><?= $c ?></div>
            <?php endforeach; ?>
            <div class="aisle"></div>
            <?php foreach(['D','E','F'] as $c): ?>
            <div style="width:36px;text-align:center;font-size:11px;color:#64748b;font-weight:600;"><?= $c ?></div>
            <?php endforeach; ?>
        </div>

        <div class="plane-wrap">
            <div class="plane-nose">✈</div>
            <div class="plane-body">

                <!-- BUSINESS CLASS -->
                <div class="section-label-row">
                    <span class="section-label-badge badge-business">
                        <i class="fa-solid fa-crown"></i> Business
                    </span>
                    <div class="section-divider"></div>
                </div>

                <?php for($r = 1; $r <= $business_rows_count; $r++): ?>
                <div class="seat-row">
                    <span class="row-num"><?= $r ?></span>
                    <?php foreach(['A','B','C'] as $c):
                        $sid = $r.$c;
                        $isTaken = in_array($sid, $taken);
                        $cls = $isTaken ? 'seat-taken' : 'seat-business-avail';
                    ?>
                    <button class="seat <?= $cls ?><?= (!$isTaken && $class!=='business') ? ' seat-locked' : '' ?>"
                        <?= ($isTaken || $class!=='business') ? 'disabled' : '' ?>
                        data-seat="<?= $sid ?>"
                        data-type="business"
                        onclick="selectSeat(this)"><?= $sid ?></button>
                    <?php endforeach; ?>

                    <div class="aisle"></div>

                    <?php foreach(['D','E','F'] as $c):
                        $sid = $r.$c;
                        $isTaken = in_array($sid, $taken);
                        $cls = $isTaken ? 'seat-taken' : 'seat-business-avail';
                    ?>
                    <button class="seat <?= $cls ?><?= (!$isTaken && $class!=='business') ? ' seat-locked' : '' ?>"
                        <?= ($isTaken || $class!=='business') ? 'disabled' : '' ?>
                        data-seat="<?= $sid ?>"
                        data-type="business"
                        onclick="selectSeat(this)"><?= $sid ?></button>
                    <?php endforeach; ?>
                </div>
                <?php endfor; ?>

                <!-- ECONOMY CLASS -->
                <div class="section-label-row" style="margin-top:10px;">
                    <span class="section-label-badge badge-economy">
                        <i class="fa-solid fa-person-seat"></i> Economy
                    </span>
                    <div class="section-divider"></div>
                </div>

                <?php for($r = $business_rows_count+1; $r <= $business_rows_count+$economy_rows_count; $r++): ?>
                <div class="seat-row">
                    <span class="row-num"><?= $r ?></span>
                    <?php foreach(['A','B','C'] as $c):
                        $sid = $r.$c;
                        $isTaken = in_array($sid, $taken);
                        $cls = $isTaken ? 'seat-taken' : 'seat-available';
                    ?>
                    <button class="seat <?= $cls ?><?= (!$isTaken && $class!=='economy') ? ' seat-locked' : '' ?>"
                        <?= ($isTaken || $class!=='economy') ? 'disabled' : '' ?>
                        data-seat="<?= $sid ?>"
                        data-type="economy"
                        onclick="selectSeat(this)"><?= $sid ?></button>
                    <?php endforeach; ?>

                    <div class="aisle"></div>

                    <?php foreach(['D','E','F'] as $c):
                        $sid = $r.$c;
                        $isTaken = in_array($sid, $taken);
                        $cls = $isTaken ? 'seat-taken' : 'seat-available';
                    ?>
                    <button class="seat <?= $cls ?><?= (!$isTaken && $class!=='economy') ? ' seat-locked' : '' ?>"
                        <?= ($isTaken || $class!=='economy') ? 'disabled' : '' ?>
                        data-seat="<?= $sid ?>"
                        data-type="economy"
                        onclick="selectSeat(this)"><?= $sid ?></button>
                    <?php endforeach; ?>
                </div>
                <?php endfor; ?>

            </div><!-- end plane-body -->
            <div class="plane-tail"></div>
        </div><!-- end plane-wrap -->

        <!-- LEGEND -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-dot" style="background:#1e3a5f;border:1px solid #2563eb44;"></div>
                Available (Economy)
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:#3b1f00;border:1px solid #d9770644;"></div>
                Available (Business)
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:linear-gradient(135deg,#4361ee,#7209b7);"></div>
                Your Selection
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:#3b1219;border:1px solid #ef444433;"></div>
                Taken
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);opacity:0.5;"></div>
                Not your class
            </div>
        </div>

    </div><!-- end plane-card -->

    <!-- RIGHT PANEL -->
    <div class="right-panel">

        <!-- Flight Summary -->
        <div class="glass-card">
            <div class="card-title" style="margin-bottom:14px;">
                <i class="fa-solid fa-circle-info"></i> Flight Info
            </div>
            <div class="flight-info-row">
                <i class="fa-solid fa-plane"></i>
                <span class="fi-label">Flight</span>
                <strong><?= htmlspecialchars($flight_no) ?></strong>
            </div>
            <div class="flight-info-row">
                <i class="fa-solid fa-chair"></i>
                <span class="fi-label">Class</span>
                <strong><?= ucfirst($class) ?></strong>
            </div>
            <div class="flight-info-row">
                <i class="fa-solid fa-users"></i>
                <span class="fi-label">Passengers</span>
                <strong><?= $no_of_pass ?></strong>
            </div>
            <div class="flight-info-row">
                <i class="fa-solid fa-jet-fighter"></i>
                <span class="fi-label">Aircraft</span>
                <strong><?= htmlspecialchars($jet_type) ?></strong>
            </div>
            <div class="flight-info-row">
                <i class="fa-solid fa-ticket"></i>
                <span class="fi-label">PNR</span>
                <strong><?= htmlspecialchars($pnr) ?></strong>
            </div>
        </div>

        <!-- Selected Seats -->
        <div class="glass-card">
            <div class="card-title" style="margin-bottom:14px;">
                <i class="fa-solid fa-check-circle"></i>
                Selected Seats
            </div>
            <div class="selected-list" id="selectedList">
                <div class="no-seat-yet" id="noSeatMsg">
                    No seats selected yet.<br>Click seats on the map.
                </div>
            </div>
            <div class="progress-info" style="margin-top:14px;">
                <span id="selCount">0</span> of <span><?= $no_of_pass ?></span> seats selected
            </div>
        </div>

        <!-- Confirm Form -->
        <form method="POST" action="save_seats.php" id="seatForm">
            <input type="hidden" name="seats" id="seatsInput">
            <input type="hidden" name="pnr" value="<?= htmlspecialchars($pnr) ?>">
            <button type="button" class="confirm-btn" id="confirmBtn" disabled onclick="submitSeats()">
                <i class="fa-solid fa-check"></i>
                Confirm Seats &amp; Continue
            </button>
            <div class="progress-info">
                Select all <?= $no_of_pass ?> seat(s) to continue
            </div>
        </form>

    </div>
</div>

<script>
const NEEDED   = <?= (int)$no_of_pass ?>;
let selected   = [];

function selectSeat(btn){
    const seat = btn.dataset.seat;
    const type = btn.dataset.type;

    if(btn.classList.contains('seat-selected')){
        // deselect
        selected = selected.filter(s => s !== seat);
        btn.classList.remove('seat-selected');
        btn.classList.add(type === 'business' ? 'seat-business-avail' : 'seat-available');
    } else {
        if(selected.length >= NEEDED){
            alert('You can only select ' + NEEDED + ' seat(s).\nDeselect one first.');
            return;
        }
        selected.push(seat);
        btn.classList.remove('seat-available','seat-business-avail');
        btn.classList.add('seat-selected');
    }

    updatePanel();
}

function updatePanel(){
    const list   = document.getElementById('selectedList');
    const noMsg  = document.getElementById('noSeatMsg');
    const count  = document.getElementById('selCount');
    const btn    = document.getElementById('confirmBtn');

    count.textContent = selected.length;

    if(selected.length === 0){
        list.innerHTML = '<div class="no-seat-yet" id="noSeatMsg">No seats selected yet.<br>Click seats on the map.</div>';
    } else {
        let html = '';
        selected.forEach((s, i) => {
            html += `<div class="selected-item">
                        <div>
                            <div class="pass-label">Passenger ${i+1}</div>
                        </div>
                        <div class="seat-label"><i class="fa-solid fa-chair"></i> ${s}</div>
                     </div>`;
        });
        list.innerHTML = html;
    }

    btn.disabled = (selected.length < NEEDED);
}

function submitSeats(){
    document.getElementById('seatsInput').value = selected.join(',');
    document.getElementById('seatForm').submit();
}
</script>

</body>
</html>