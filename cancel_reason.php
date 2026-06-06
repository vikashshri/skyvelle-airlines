<?php
session_start();
if(!isset($_SESSION['userid'])){ header("Location: login.php"); exit(); }
if(!isset($_POST['pnr']) && !isset($_SESSION['cancel_pnr'])){ header("Location: cancel_booked_tickets.php"); exit(); }
$pnr = $_POST['pnr'] ?? $_SESSION['cancel_pnr'];
$_SESSION['cancel_pnr'] = $pnr;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cancellation Reason — Skyvelle</title>
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
    padding:40px; max-width:600px; margin:0 auto;
}
.card-title{ font-size:22px; font-weight:700; margin-bottom:6px; display:flex; align-items:center; gap:12px; }
.card-title i{ color:#f87171; }
.card-sub{ color:#64748b; font-size:14px; margin-bottom:32px; }

/* REASON CARDS */
.reasons-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:24px; }

.reason-option{ position:relative; }

.reason-option input[type="radio"]{
    position:absolute; opacity:0; width:0; height:0;
}

.reason-label{
    display:flex; align-items:center; gap:14px;
    background:rgba(255,255,255,0.04);
    border:1.5px solid rgba(255,255,255,0.08);
    border-radius:14px; padding:16px 18px;
    cursor:pointer; transition:0.2s;
    font-size:14px;
}

.reason-label:hover{
    background:rgba(255,255,255,0.08);
    border-color:rgba(255,255,255,0.18);
}

.reason-option input[type="radio"]:checked + .reason-label{
    background:rgba(67,97,238,0.15);
    border-color:rgba(67,97,238,0.5);
    color:white;
}

.reason-icon{
    width:38px; height:38px; border-radius:10px;
    background:rgba(255,255,255,0.06);
    display:flex; align-items:center; justify-content:center;
    font-size:16px; flex-shrink:0;
    transition:0.2s;
}

.reason-option input[type="radio"]:checked + .reason-label .reason-icon{
    background:rgba(67,97,238,0.3);
    color:#93c5fd;
}

/* OTHER TEXTAREA */
.other-field{
    margin-bottom:24px; display:none;
}
.other-field.show{ display:block; }
.other-field label{
    display:block; font-size:12px; letter-spacing:1.5px;
    text-transform:uppercase; color:#94a3b8; margin-bottom:10px;
}
.other-field textarea{
    width:100%; padding:14px 16px;
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:14px; color:white;
    font-family:'Poppins',sans-serif; font-size:14px;
    outline:none; resize:vertical; min-height:90px;
    transition:0.2s;
}
.other-field textarea:focus{ border-color:rgba(76,201,240,0.4); }
.other-field textarea::placeholder{ color:rgba(255,255,255,0.25); }

.btn-next{
    width:100%; padding:16px;
    background:linear-gradient(135deg,#ef4444,#b91c1c);
    color:white; border:none; border-radius:14px;
    font-family:'Poppins',sans-serif; font-size:16px; font-weight:600;
    cursor:pointer; transition:0.3s;
    display:flex; align-items:center; justify-content:center; gap:10px;
}
.btn-next:hover{ transform:translateY(-2px); box-shadow:0 10px 25px rgba(239,68,68,0.35); }
.btn-back{ display:flex; align-items:center; justify-content:center; gap:8px; color:#64748b; text-decoration:none; font-size:14px; margin-top:14px; transition:0.2s; text-align:center; }
.btn-back:hover{ color:white; }
</style>
</head>
<body>
<div class="navbar">
    <div class="logo">✈ Skyvelle</div>
</div>

<div class="steps-bar">
    <div class="step done"><div class="step-circle"><i class="fa-solid fa-check" style="font-size:12px;"></i></div><div class="step-label">Enter PNR</div></div>
    <div class="step-line done"></div>
    <div class="step done"><div class="step-circle"><i class="fa-solid fa-check" style="font-size:12px;"></i></div><div class="step-label">Review</div></div>
    <div class="step-line done"></div>
    <div class="step active"><div class="step-circle">3</div><div class="step-label">Reason</div></div>
    <div class="step-line"></div>
    <div class="step"><div class="step-circle">4</div><div class="step-label">Confirm</div></div>
</div>

<div class="card">
    <div class="card-title"><i class="fa-solid fa-circle-question"></i> Why are you cancelling?</div>
    <div class="card-sub">Your feedback helps us improve. Please select a reason for cancellation.</div>

    <form action="cancel_confirm.php" method="post">
        <input type="hidden" name="pnr" value="<?= htmlspecialchars($pnr) ?>">

        <div class="reasons-grid">

            <div class="reason-option">
                <input type="radio" name="reason" id="r1" value="Change in travel plans" required>
                <label class="reason-label" for="r1">
                    <div class="reason-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                    Change in travel plans
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r2" value="Medical emergency">
                <label class="reason-label" for="r2">
                    <div class="reason-icon"><i class="fa-solid fa-hospital"></i></div>
                    Medical emergency
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r3" value="Business or work cancellation">
                <label class="reason-label" for="r3">
                    <div class="reason-icon"><i class="fa-solid fa-briefcase"></i></div>
                    Business / work cancellation
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r4" value="Family emergency">
                <label class="reason-label" for="r4">
                    <div class="reason-icon"><i class="fa-solid fa-people-roof"></i></div>
                    Family emergency
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r5" value="Found a better fare">
                <label class="reason-label" for="r5">
                    <div class="reason-icon"><i class="fa-solid fa-tag"></i></div>
                    Found a better fare
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r6" value="Weather concerns">
                <label class="reason-label" for="r6">
                    <div class="reason-icon"><i class="fa-solid fa-cloud-bolt"></i></div>
                    Weather concerns
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r7" value="Visa or document issue">
                <label class="reason-label" for="r7">
                    <div class="reason-icon"><i class="fa-solid fa-passport"></i></div>
                    Visa / document issue
                </label>
            </div>

            <div class="reason-option">
                <input type="radio" name="reason" id="r8" value="Other">
                <label class="reason-label" for="r8" onclick="toggleOther(true)">
                    <div class="reason-icon"><i class="fa-solid fa-ellipsis"></i></div>
                    Other reason
                </label>
            </div>

        </div>

        <div class="other-field" id="otherField">
            <label>Please describe your reason</label>
            <textarea name="other_reason" placeholder="Tell us more..."></textarea>
        </div>

        <button type="submit" class="btn-next">
            <i class="fa-solid fa-arrow-right"></i> Continue to Confirm
        </button>
    </form>
    <a href="cancel_review.php" class="btn-back" onclick="history.back(); return false;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<script>
// show/hide other textarea
document.querySelectorAll('input[name="reason"]').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('otherField').classList.toggle('show', r.value === 'Other' && r.checked);
    });
});
function toggleOther(show){ document.getElementById('otherField').classList.toggle('show', show); }
</script>
</body>
</html>
