<?php
session_start();
if(!isset($_SESSION['userid'])){ header("Location: login.php"); exit(); }

$flight_no         = $_SESSION['flight_no']        ?? '';
$journey_date      = $_SESSION['journey_date']      ?? date('Y-m-d');
$no_of_pass        = $_SESSION['no_of_pass']        ?? 1;
$total_no_of_meals = $_SESSION['total_no_of_meals'] ?? 0;
$class             = $_SESSION['class']             ?? 'economy';
$pnr               = $_SESSION['pnr']               ?? '';

$payment_id   = rand(100000000, 999999999);
$payment_date = date('Y-m-d');
$_SESSION['payment_id']   = $payment_id;
$_SESSION['payment_date'] = $payment_date;

require_once('Database Connection file/mysqli_connect.php');

$col   = $class === 'business' ? 'price_business' : 'price_economy';
$q     = "SELECT $col FROM flight_details WHERE flight_no=? AND departure_date=?";
$stmt  = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "ss", $flight_no, $journey_date);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $ticket_price);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

/* also get route for display */
$rq = "SELECT from_city, to_city, departure_time FROM flight_details WHERE flight_no=? AND departure_date=?";
$rst = mysqli_prepare($dbc, $rq);
mysqli_stmt_bind_param($rst, "ss", $flight_no, $journey_date);
mysqli_stmt_execute($rst);
mysqli_stmt_bind_result($rst, $from_city, $to_city, $dep_time);
mysqli_stmt_fetch($rst);
mysqli_stmt_close($rst);
mysqli_close($dbc);

$ticket_price   = $ticket_price ?? 5000;
$t_ticket       = $no_of_pass  * $ticket_price;
$t_meal         = $total_no_of_meals * 250;
$t_insurance    = ($_SESSION['insurance']        ?? 'no') === 'yes' ? 100 * $no_of_pass : 0;
$t_priority     = ($_SESSION['priority_checkin'] ?? 'no') === 'yes' ? 200 * $no_of_pass : 0;
$t_lounge       = ($_SESSION['lounge_access']    ?? 'no') === 'yes' ? 300 * $no_of_pass : 0;
$total_amount   = $t_ticket + $t_meal + $t_insurance + $t_priority + $t_lounge;
$_SESSION['total_amount'] = $total_amount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment — Skyvelle Airlines</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
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
    padding:16px 28px; 
}
.logo{ font-size:26px; font-weight:700; }
.nav-links{ display:flex; gap:12px; }
.nav-links a{ text-decoration:none; color:white; padding:10px 16px; border-radius:10px; transition:0.3s; font-size:14px; }
.nav-links a:hover{ background:linear-gradient(135deg,#4361ee,#7209b7); }

/* LAYOUT */
.page-layout{
    display:grid;
    grid-template-columns:1fr 400px;
    gap:28px;
    max-width:1100px;
    margin:0 auto;
}

/* GLASS */
.glass-card{
    
    border:1px solid rgba(255,255,255,0.09);
    border-radius:26px;
    padding:32px;
}

.card-title{
    font-size:20px; font-weight:700;
    margin-bottom:24px;
    display:flex; align-items:center; gap:10px; color:#dbeafe;
}
.card-title i{ color:#4cc9f0; }

/* ── CREDIT CARD VISUAL ── */
.cc-visual{
    width:100%; max-width:360px;
    height:200px;
    border-radius:20px;
    background:linear-gradient(135deg,#1a2d5a,#4361ee,#7209b7);
    padding:28px;
    position:relative;
    margin:0 auto 28px;
    box-shadow:0 20px 50px rgba(67,97,238,0.35);
    overflow:hidden;
    transition:0.4s;
}

.cc-visual::before{
    content:'';
    position:absolute;
    top:-60px; right:-40px;
    width:220px; height:220px;
    border-radius:50%;
    background:rgba(255,255,255,0.05);
}

.cc-visual::after{
    content:'';
    position:absolute;
    bottom:-80px; left:-30px;
    width:200px; height:200px;
    border-radius:50%;
    background:rgba(255,255,255,0.04);
}

.cc-chip{
    width:44px; height:34px;
    background:linear-gradient(135deg,#e8c97a,#c9a84c);
    border-radius:6px;
    margin-bottom:24px;
    position:relative;
    z-index:1;
}

.cc-number{
    font-family:'Space Mono', monospace;
    font-size:20px;
    letter-spacing:3px;
    margin-bottom:20px;
    position:relative; z-index:1;
    color:rgba(255,255,255,0.9);
}

.cc-bottom{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    position:relative; z-index:1;
}

.cc-label{ font-size:9px; letter-spacing:2px; color:rgba(255,255,255,0.5); text-transform:uppercase; margin-bottom:3px; }
.cc-value{ font-size:14px; font-weight:600; font-family:'Space Mono',monospace; letter-spacing:1px; }

.cc-brand{ font-size:28px; color:rgba(255,255,255,0.8); }

/* PAYMENT MODE TABS */
.mode-tabs{
    display:flex; gap:10px; margin-bottom:28px; flex-wrap:wrap;
}

.mode-tab{
    flex:1; min-width:90px;
    padding:12px 8px;
    border-radius:12px;
    border:1.5px solid rgba(255,255,255,0.08);
    background:rgba(255,255,255,0.04);
    color:rgba(255,255,255,0.5);
    cursor:pointer; transition:0.2s;
    text-align:center; font-size:13px; font-weight:500;
}

.mode-tab i{ display:block; font-size:20px; margin-bottom:5px; }

.mode-tab.active{
    background:rgba(67,97,238,0.18);
    border-color:rgba(67,97,238,0.5);
    color:white;
}

.mode-tab:hover{ background:rgba(255,255,255,0.08); color:white; }

/* FORM FIELDS */
.field{ margin-bottom:20px; }

.field label{
    display:block; font-size:11px; letter-spacing:1.5px;
    text-transform:uppercase; color:#94a3b8; margin-bottom:8px;
}

.field input{
    width:100%; padding:14px 16px;
    background:rgba(255,255,255,0.06);
    border:1.5px solid rgba(255,255,255,0.09);
    border-radius:13px; color:white;
    font-family:'Poppins',sans-serif; font-size:15px; outline:none;
    transition:0.2s;
}

.field input:focus{
    border-color:rgba(67,97,238,0.5);
    background:rgba(67,97,238,0.08);
}

.field input::placeholder{ color:rgba(255,255,255,0.2); }

.field-row{ display:grid; grid-template-columns:1fr 1fr; gap:14px; }

.field-icon{ position:relative; }
.field-icon input{ padding-right:44px; }
.field-icon .ico{
    position:absolute; right:14px; top:50%;
    transform:translateY(-50%);
    color:#4cc9f0; font-size:15px; pointer-events:none;
}

/* NET BANKING SECTION */
.bank-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }

.bank-opt{
    position:relative;
}
.bank-opt input[type="radio"]{ position:absolute; opacity:0; }
.bank-opt label{
    display:flex; flex-direction:column; align-items:center; gap:6px;
    padding:14px 8px; border-radius:12px;
    border:1.5px solid rgba(255,255,255,0.08);
    background:rgba(255,255,255,0.04);
    cursor:pointer; transition:0.2s; font-size:12px; text-align:center;
    color:rgba(255,255,255,0.6);
}
.bank-opt label i{ font-size:22px; color:#4cc9f0; }
.bank-opt input:checked + label{
    background:rgba(67,97,238,0.18);
    border-color:rgba(67,97,238,0.5);
    color:white;
}
.bank-opt label:hover{ background:rgba(255,255,255,0.08); }

/* UPI */
.upi-section .field input{ letter-spacing:1px; }

/* SECTION TOGGLE */
.payment-section{ display:none; }
.payment-section.show{ display:block; }

/* ── RIGHT: SUMMARY ── */
.summary-card{ position:sticky; top:20px; }

.route-mini{
    display:flex; align-items:center; justify-content:space-between;
    background:linear-gradient(135deg,rgba(29,78,216,0.2),rgba(126,34,206,0.2));
    border:1px solid rgba(255,255,255,0.08);
    border-radius:16px; padding:18px 20px; margin-bottom:20px;
}
.rc .lbl{ font-size:10px; color:#94a3b8; margin-bottom:3px; letter-spacing:1px; }
.rc .city{ font-size:18px; font-weight:700; }
.rc .time{ font-size:12px; color:#4cc9f0; margin-top:2px; }
.ra i{ font-size:20px; color:#4cc9f0; }

.summary-row{
    display:flex; justify-content:space-between;
    padding:12px 0; border-bottom:1px solid rgba(255,255,255,0.06);
    font-size:14px;
}
.summary-row:last-of-type{ border-bottom:none; }
.summary-row .lbl{ color:#64748b; }
.summary-row .val{ font-weight:500; }

.total-row{
    display:flex; justify-content:space-between; align-items:center;
    background:rgba(67,97,238,0.12);
    border:1px solid rgba(67,97,238,0.25);
    border-radius:14px; padding:16px 18px; margin:16px 0;
}
.total-row .lbl{ font-size:14px; color:#93c5fd; }
.total-row .val{ font-size:28px; font-weight:700; color:#4cc9f0; }

.pid-box{
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:12px; padding:12px 16px;
    font-size:12px; color:#64748b; margin-bottom:20px;
    display:flex; align-items:center; gap:10px;
}
.pid-box strong{ color:#94a3b8; font-family:'Space Mono',monospace; }

/* PAY BUTTON */
.pay-btn{
    width:100%; padding:17px;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white; border:none; border-radius:16px;
    font-family:'Poppins',sans-serif; font-size:17px; font-weight:700;
    cursor:pointer; transition:0.3s;
    display:flex; align-items:center; justify-content:center; gap:12px;
}
.pay-btn:hover{ transform:translateY(-3px); box-shadow:0 12px 30px rgba(67,97,238,0.45); }

.secure-note{
    display:flex; align-items:center; justify-content:center;
    gap:6px; font-size:12px; color:#475569; margin-top:12px;
}
.secure-note i{ color:#4ade80; }

/* RESPONSIVE */
@media(max-width:900px){
    .page-layout{ grid-template-columns:1fr; }
    .summary-card{ position:static; }
    .navbar{ flex-direction:column; gap:12px; }
}
</style>
</head>
<body>

<div class="navbar">
    <div class="logo">✈ Skyvelle</div>
</div>

<form action="payment_details_form_handler.php" method="post" id="payForm">
<input type="hidden" name="payment_mode" id="payModeInput" value="credit card">

<div class="page-layout">

    <!-- LEFT: PAYMENT FORM -->
    <div>

        <!-- CARD VISUAL -->
        <div class="glass-card" style="margin-bottom:24px;">
            <div class="card-title"><i class="fa-solid fa-credit-card"></i> Payment Details</div>

            <div class="cc-visual" id="ccVisual">
                <div class="cc-chip"></div>
                <div class="cc-number" id="ccDisplay">•••• •••• •••• ••••</div>
                <div class="cc-bottom">
                    <div>
                        <div class="cc-label">Card Holder</div>
                        <div class="cc-value" id="ccName">YOUR NAME</div>
                    </div>
                    <div>
                        <div class="cc-label">Expires</div>
                        <div class="cc-value" id="ccExpiry">MM/YY</div>
                    </div>
                    <div class="cc-brand" id="ccBrand"><i class="fa-brands fa-cc-visa"></i></div>
                </div>
            </div>

            <!-- MODE TABS -->
            <div class="mode-tabs">
                <div class="mode-tab active" onclick="switchMode('credit')">
                    <i class="fa-solid fa-credit-card"></i> Credit Card
                </div>
                <div class="mode-tab" onclick="switchMode('debit')">
                    <i class="fa-solid fa-credit-card"></i> Debit Card
                </div>
                <div class="mode-tab" onclick="switchMode('netbanking')">
                    <i class="fa-solid fa-building-columns"></i> Net Banking
                </div>
                <div class="mode-tab" onclick="switchMode('upi')">
                    <i class="fa-solid fa-mobile-screen"></i> UPI
                </div>
            </div>

            <!-- CREDIT / DEBIT CARD FIELDS -->
            <div class="payment-section show" id="sec-credit">
                <div class="field field-icon">
                    <label>Card Number</label>
                    <input type="text" id="cardNum" maxlength="19"
                        placeholder="1234 5678 9012 3456"
                        oninput="formatCard(this)" autocomplete="cc-number">
                    <span class="ico"><i class="fa-solid fa-lock"></i></span>
                </div>
                <div class="field">
                    <label>Cardholder Name</label>
                    <input type="text" id="cardName" placeholder="Name as on card"
                        oninput="updateName(this)" autocomplete="cc-name">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Expiry Date</label>
                        <input type="text" id="cardExpiry" maxlength="5"
                            placeholder="MM/YY" oninput="formatExpiry(this)"
                            autocomplete="cc-exp">
                    </div>
                    <div class="field field-icon">
                        <label>CVV</label>
                        <input type="password" id="cardCvv" maxlength="4"
                            placeholder="•••" autocomplete="cc-csc">
                        <span class="ico"><i class="fa-solid fa-shield-halved"></i></span>
                    </div>
                </div>
            </div>

            <!-- DEBIT SAME AS CREDIT -->
            <div class="payment-section" id="sec-debit">
                <div class="field field-icon">
                    <label>Card Number</label>
                    <input type="text" maxlength="19"
                        placeholder="1234 5678 9012 3456"
                        oninput="formatCard(this);syncDebit(this)" autocomplete="cc-number">
                    <span class="ico"><i class="fa-solid fa-lock"></i></span>
                </div>
                <div class="field">
                    <label>Cardholder Name</label>
                    <input type="text" placeholder="Name as on card"
                        oninput="updateName(this)" autocomplete="cc-name">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Expiry Date</label>
                        <input type="text" maxlength="5" placeholder="MM/YY"
                            oninput="formatExpiry(this)" autocomplete="cc-exp">
                    </div>
                    <div class="field field-icon">
                        <label>CVV</label>
                        <input type="password" maxlength="4" placeholder="•••" autocomplete="cc-csc">
                        <span class="ico"><i class="fa-solid fa-shield-halved"></i></span>
                    </div>
                </div>
            </div>

            <!-- NET BANKING -->
            <div class="payment-section" id="sec-netbanking">
                <p style="color:#94a3b8;font-size:13px;margin-bottom:18px;">Select your bank to continue</p>
                <div class="bank-grid">
                    <?php
                    $banks = [
                        ['fa-solid fa-b','SBI'],
                        ['fa-solid fa-h','HDFC'],
                        ['fa-solid fa-i','ICICI'],
                        ['fa-solid fa-a','Axis'],
                        ['fa-solid fa-k','Kotak'],
                        ['fa-solid fa-ellipsis','Other'],
                    ];
                    foreach($banks as $i => [$ico, $name]):
                    ?>
                    <div class="bank-opt">
                        <input type="radio" name="bank" id="bank<?=$i?>" value="<?=$name?>" <?=$i===0?'checked':''?>>
                        <label for="bank<?=$i?>">
                            <i class="<?=$ico?>"></i>
                            <?=$name?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="field" style="margin-top:20px;">
                    <label>Net Banking User ID</label>
                    <input type="text" placeholder="Enter your user ID">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" placeholder="Enter password">
                </div>
            </div>

            <!-- UPI -->
            <div class="payment-section upi-section" id="sec-upi">
                <p style="color:#94a3b8;font-size:13px;margin-bottom:18px;">Enter your UPI ID to pay instantly</p>
                <div class="field field-icon">
                    <label>UPI ID</label>
                    <input type="text" placeholder="yourname@upi">
                    <span class="ico"><i class="fa-solid fa-at"></i></span>
                </div>
                <p style="font-size:12px;color:#475569;margin-top:6px;">
                    <i class="fa-solid fa-circle-info" style="color:#4cc9f0;"></i>
                    A payment request will be sent to your UPI app
                </p>
            </div>

        </div>
    </div>

    <!-- RIGHT: SUMMARY -->
    <div class="summary-card">
        <div class="glass-card">
            <div class="card-title"><i class="fa-solid fa-receipt"></i> Order Summary</div>

            <!-- Route -->
            <div class="route-mini">
                <div class="rc">
                    <div class="lbl">FROM</div>
                    <div class="city"><?= strtoupper($from_city ?? '—') ?></div>
                    <div class="time"><?= $dep_time ?? '' ?></div>
                </div>
                <div class="ra"><i class="fa-solid fa-plane"></i></div>
                <div class="rc" style="text-align:right;">
                    <div class="lbl">TO</div>
                    <div class="city"><?= strtoupper($to_city ?? '—') ?></div>
                    <div class="time"><?= $journey_date ?></div>
                </div>
            </div>

            <!-- Breakdown -->
            <div class="summary-row">
                <span class="lbl">
                    Base Fare (<?= $no_of_pass ?> × ₹<?= number_format($ticket_price) ?>)
                </span>
                <span class="val">₹<?= number_format($t_ticket) ?></span>
            </div>
            <?php if($t_meal > 0): ?>
            <div class="summary-row">
                <span class="lbl">Meal Charges</span>
                <span class="val">₹<?= number_format($t_meal) ?></span>
            </div>
            <?php endif; ?>
            <?php if($t_priority > 0): ?>
            <div class="summary-row">
                <span class="lbl">Priority Check-in</span>
                <span class="val">₹<?= number_format($t_priority) ?></span>
            </div>
            <?php endif; ?>
            <?php if($t_lounge > 0): ?>
            <div class="summary-row">
                <span class="lbl">Lounge Access</span>
                <span class="val">₹<?= number_format($t_lounge) ?></span>
            </div>
            <?php endif; ?>
            <?php if($t_insurance > 0): ?>
            <div class="summary-row">
                <span class="lbl">Travel Insurance</span>
                <span class="val">₹<?= number_format($t_insurance) ?></span>
            </div>
            <?php endif; ?>

            <div class="total-row">
                <span class="lbl"><i class="fa-solid fa-indian-rupee-sign"></i> Total Amount</span>
                <span class="val">₹<?= number_format($total_amount) ?></span>
            </div>

            <div class="pid-box">
                <i class="fa-solid fa-hashtag" style="color:#4cc9f0;"></i>
                Payment ID: <strong><?= $payment_id ?></strong>
            </div>

            <button type="submit" name="Pay_Now" class="pay-btn">
                <i class="fa-solid fa-lock"></i>
                Pay ₹<?= number_format($total_amount) ?> Securely
            </button>
            <div class="secure-note">
                <i class="fa-solid fa-shield-halved"></i>
                256-bit SSL encrypted · Safe & Secure
            </div>
        </div>
    </div>

</div>
<input type="hidden" name="card_holder_name" id="fCardName" value="">
<input type="hidden" name="card_number"      id="fCardNum"  value="">
<input type="hidden" name="expiry_month"     id="fExpMonth" value="">
<input type="hidden" name="expiry_year"      id="fExpYear"  value="">
</form>

<script>
// Mode switcher
const modes = ['credit','debit','netbanking','upi'];
const modeValues = {'credit':'credit card','debit':'debit card','netbanking':'net banking','upi':'upi'};

function switchMode(mode){
    modes.forEach(m => {
        document.getElementById('sec-'+m).classList.remove('show');
    });
    document.getElementById('sec-'+mode).classList.add('show');
    document.querySelectorAll('.mode-tab').forEach((t,i) => {
        t.classList.toggle('active', modes[i] === mode);
    });
    document.getElementById('payModeInput').value = modeValues[mode];

    // Update card visual visibility
    const showCard = mode === 'credit' || mode === 'debit';
    document.getElementById('ccVisual').style.display = showCard ? 'block' : 'none';
}

// Live card number format & display
function formatCard(input){
    document.getElementById('fCardNum').value = input.value.replace(/\D/g,'');
    let v = input.value.replace(/\D/g,'').substring(0,16);
    input.value = v.replace(/(.{4})/g,'$1 ').trim();
    let display = v.padEnd(16,'•');
    document.getElementById('ccDisplay').textContent =
        display.replace(/(.{4})/g,'$1 ').trim();

    // Brand detection
    const brand = document.getElementById('ccBrand');
    if(/^4/.test(v))       brand.innerHTML = '<i class="fa-brands fa-cc-visa"></i>';
    else if(/^5[1-5]/.test(v)) brand.innerHTML = '<i class="fa-brands fa-cc-mastercard"></i>';
    else if(/^3[47]/.test(v))  brand.innerHTML = '<i class="fa-brands fa-cc-amex"></i>';
    else if(/^6/.test(v))  brand.innerHTML = '<i class="fa-brands fa-cc-discover"></i>';
    else brand.innerHTML = '<i class="fa-brands fa-cc-visa"></i>';
}

function updateName(input){
    document.getElementById('fCardName').value = input.value;
    document.getElementById('ccName').textContent = input.value.toUpperCase() || 'YOUR NAME';
}

function formatExpiry(input){
    let v = input.value.replace(/\D/g,'');
    if(v.length >= 2) v = v.substring(0,2)+'/'+v.substring(2,4);
    input.value = v;
    document.getElementById('ccExpiry').textContent = input.value || 'MM/YY';
    const parts = input.value.split('/');
    document.getElementById('fExpMonth').value = parts[0] || '';
    document.getElementById('fExpYear').value  = parts[1] || '';
}
</script>

</body>
</html>