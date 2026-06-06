<?php
session_start();
if(!isset($_SESSION['userid'])){ header("Location: login_page.php"); exit(); }

$customer_id = $_SESSION['userid'];
$success_msg = '';
$error_msg   = '';

require_once('Database Connection file/mysqli_connect.php');

/* ── Handle form submission ── */
if(isset($_POST['update_profile'])){

    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone_no']?? '');
    $address = trim($_POST['address'] ?? '');
    $new_pwd = trim($_POST['new_pwd'] ?? '');
    $cur_pwd = trim($_POST['cur_pwd'] ?? '');

    /* validate */
    if(empty($name) || empty($email) || empty($phone)){
        $error_msg = 'Name, email and phone are required.';
    } else {

        /* if changing password, verify current first */
        if(!empty($new_pwd)){
            $vq = "SELECT pwd FROM customer WHERE customer_id=?";
            $vst = mysqli_prepare($dbc, $vq);
            mysqli_stmt_bind_param($vst, "s", $customer_id);
            mysqli_stmt_execute($vst);
            mysqli_stmt_bind_result($vst, $db_pwd);
            mysqli_stmt_fetch($vst);
            mysqli_stmt_close($vst);

            if($cur_pwd !== $db_pwd){
                $error_msg = 'Current password is incorrect.';
            } else {
                $uq = "UPDATE customer SET name=?, email=?, phone_no=?, address=?, pwd=? WHERE customer_id=?";
                $ust = mysqli_prepare($dbc, $uq);
                mysqli_stmt_bind_param($ust, "ssssss", $name, $email, $phone, $address, $new_pwd, $customer_id);
                mysqli_stmt_execute($ust);
                mysqli_stmt_close($ust);
                $success_msg = 'Profile and password updated successfully!';
            }
        } else {
            $uq = "UPDATE customer SET name=?, email=?, phone_no=?, address=? WHERE customer_id=?";
            $ust = mysqli_prepare($dbc, $uq);
            mysqli_stmt_bind_param($ust, "sssss", $name, $email, $phone, $address, $customer_id);
            mysqli_stmt_execute($ust);
            mysqli_stmt_close($ust);
            $success_msg = 'Profile updated successfully!';
        }
    }
}

/* ── Fetch current data ── */
$q = "SELECT name, email, phone_no, address, pwd FROM customer WHERE customer_id=?";
$stmt = mysqli_prepare($dbc, $q);
mysqli_stmt_bind_param($stmt, "s", $customer_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $email, $phone, $address, $pwd);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);

$initials = strtoupper(substr($name ?? 'U', 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile — Skyvelle</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
    --gold:#c9a84c; --gold-light:#e8c97a;
    --ff-disp:'Playfair Display',serif;
}
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
body{
    background:linear-gradient(rgba(3,8,25,0.92),rgba(3,8,25,0.95)),
    url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');
    background-size:cover; background-position:center; background-attachment:fixed;
    color:white; min-height:100vh; padding:30px;
}

/* NAVBAR */
.navbar{
    display:flex; justify-content:space-between; align-items:center;
    padding:16px 28px;  margin-bottom:30px;
}
.logo{ font-family:var(--ff-disp); font-size:28px; font-weight:600; color:white; text-decoration:none; }
.nav-links{ display:flex; gap:12px; }
.nav-links a{
    text-decoration:none; color:white; padding:10px 16px;
    border-radius:10px; transition:0.3s; font-size:14px;
}
.nav-links a:hover{ background:linear-gradient(135deg,#4361ee,#7209b7); }

/* BREADCRUMB */
.breadcrumb{
    display:flex; align-items:center; gap:8px;
    font-size:13px; color:#475569; margin-bottom:28px;
    max-width:900px; margin-left:auto; margin-right:auto;
}
.breadcrumb a{ color:#4cc9f0; text-decoration:none; }
.breadcrumb a:hover{ text-decoration:underline; }
.breadcrumb i{ font-size:10px; }

/* LAYOUT */
.page-layout{
    display:grid;
    grid-template-columns:280px 1fr;
    gap:28px;
    max-width:900px;
    margin:0 auto;
}

/* GLASS */
.glass-card{
    background:rgba(255,255,255,0.07);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,0.09);
    border-radius:26px; padding:30px;
}

/* AVATAR PANEL */
.avatar-panel{ text-align:center; }

.avatar-big{
    width:110px; height:110px; border-radius:50%;
    background:linear-gradient(135deg,#1d4ed8,#7e22ce);
    border:4px solid rgba(255,255,255,0.2);
    display:flex; align-items:center; justify-content:center;
    font-size:44px; font-weight:700;
    margin:0 auto 16px;
    font-family:var(--ff-disp);
}

.avatar-name{
    font-family:var(--ff-disp);
    font-size:22px; font-weight:600; margin-bottom:4px;
}

.avatar-id{
    font-size:12px; color:#64748b; margin-bottom:20px;
}

.avatar-divider{
    height:1px; background:rgba(255,255,255,0.07); margin:20px 0;
}

/* SIDE NAV */
.side-nav a{
    display:flex; align-items:center; gap:12px;
    padding:12px 14px; border-radius:12px;
    text-decoration:none; color:rgba(255,255,255,0.5);
    font-size:14px; transition:0.2s; margin-bottom:4px;
}
.side-nav a:hover{ background:rgba(255,255,255,0.06); color:white; }
.side-nav a.active{
    background:rgba(67,97,238,0.18);
    border:1px solid rgba(67,97,238,0.3);
    color:white;
}
.side-nav a i{ width:18px; text-align:center; color:#4cc9f0; }

/* CARD TITLE */
.card-title{
    font-family:var(--ff-disp);
    font-size:24px; font-weight:600; margin-bottom:6px;
    display:flex; align-items:center; gap:10px;
}
.card-title i{ color:#4cc9f0; font-size:20px; }
.card-sub{ color:#64748b; font-size:13px; margin-bottom:28px; }

/* SECTION DIVIDER */
.section-sep{
    font-size:11px; letter-spacing:2px; text-transform:uppercase;
    color:var(--gold); margin:28px 0 16px;
    display:flex; align-items:center; gap:10px;
}
.section-sep::after{
    content:''; flex:1; height:1px;
    background:rgba(201,168,76,0.2);
}

/* FORM FIELDS */
.field-grid{ display:grid; grid-template-columns:1fr 1fr; gap:18px; }
.field-full{ grid-column:1/-1; }

.field{ margin-bottom:0; }

.field label{
    display:block; font-size:11px; letter-spacing:1.5px;
    text-transform:uppercase; color:#94a3b8; margin-bottom:8px;
}

.field .input-wrap{ position:relative; }

.field input{
    width:100%; padding:13px 16px 13px 42px;
    background:rgba(255,255,255,0.05);
    border:1.5px solid rgba(255,255,255,0.09);
    border-radius:13px; color:white;
    font-family:'Poppins',sans-serif; font-size:14px; outline:none;
    transition:0.2s;
}

.field input:focus{
    border-color:rgba(67,97,238,0.5);
    background:rgba(67,97,238,0.07);
}

.field input::placeholder{ color:rgba(255,255,255,0.2); }

.field .fi{
    position:absolute; left:14px; top:50%;
    transform:translateY(-50%);
    color:#4cc9f0; font-size:14px; pointer-events:none;
}

.field .eye-btn{
    position:absolute; right:14px; top:50%;
    transform:translateY(-50%);
    background:none; border:none; color:#64748b;
    cursor:pointer; font-size:14px; padding:0;
    transition:0.2s;
}
.field .eye-btn:hover{ color:white; }

/* READ-ONLY */
.field input[readonly]{
    background:rgba(255,255,255,0.03);
    border-color:rgba(255,255,255,0.05);
    color:#475569; cursor:not-allowed;
}

/* ALERTS */
.alert{
    display:flex; align-items:center; gap:12px;
    border-radius:14px; padding:14px 18px; margin-bottom:24px;
    font-size:14px;
}
.alert-success{
    background:rgba(34,197,94,0.12);
    border:1px solid rgba(34,197,94,0.3);
    color:#86efac;
}
.alert-error{
    background:rgba(239,68,68,0.12);
    border:1px solid rgba(239,68,68,0.3);
    color:#fca5a5;
}

/* BUTTONS */
.btn-row{ display:flex; gap:14px; margin-top:28px; flex-wrap:wrap; }

.btn-save{
    flex:1; padding:14px 24px;
    background:linear-gradient(135deg,#4361ee,#7209b7);
    color:white; border:none; border-radius:14px;
    font-family:'Poppins',sans-serif; font-size:15px; font-weight:600;
    cursor:pointer; transition:0.3s;
    display:flex; align-items:center; justify-content:center; gap:10px;
    min-width:160px;
}
.btn-save:hover{ transform:translateY(-2px); box-shadow:0 10px 25px rgba(67,97,238,0.4); }

.btn-cancel{
    padding:14px 24px;
    background:rgba(255,255,255,0.06);
    color:white; border:1px solid rgba(255,255,255,0.1);
    border-radius:14px; font-family:'Poppins',sans-serif;
    font-size:15px; cursor:pointer; transition:0.3s; text-decoration:none;
    display:flex; align-items:center; gap:8px;
}
.btn-cancel:hover{ background:rgba(255,255,255,0.1); }

/* PASSWORD STRENGTH */
.pwd-strength{ margin-top:8px; }
.pwd-bar{
    height:4px; border-radius:99px;
    background:rgba(255,255,255,0.08);
    overflow:hidden; margin-bottom:4px;
}
.pwd-fill{
    height:100%; border-radius:99px;
    transition:0.3s; width:0%;
}
.pwd-text{ font-size:11px; color:#64748b; }

/* DANGER ZONE */
.danger-zone{
    background:rgba(239,68,68,0.06);
    border:1px solid rgba(239,68,68,0.2);
    border-radius:18px; padding:24px; margin-top:24px;
}
.dz-title{ font-size:15px; font-weight:600; color:#fca5a5; margin-bottom:6px; }
.dz-desc{ font-size:13px; color:#64748b; margin-bottom:16px; }
.btn-danger{
    padding:10px 20px;
    background:rgba(239,68,68,0.15);
    color:#fca5a5; border:1px solid rgba(239,68,68,0.3);
    border-radius:10px; font-family:'Poppins',sans-serif;
    font-size:13px; cursor:pointer; transition:0.2s;
    text-decoration:none; display:inline-flex; align-items:center; gap:8px;
}
.btn-danger:hover{ background:rgba(239,68,68,0.25); }

/* RESPONSIVE */
@media(max-width:860px){
    .page-layout{ grid-template-columns:1fr; }
    .field-grid{ grid-template-columns:1fr; }
}
@media(max-width:600px){
    body{ padding:15px; }
    .navbar{ flex-direction:column; gap:12px; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href=# class="logo">✈ Skyvelle</a>
    <div class="nav-links">
        <a href="homepage.php"><i class="fa-solid fa-house"></i> Home</a>
    </div>
</div>

<!-- BREADCRUMB -->
<div class="breadcrumb" style="max-width:900px;margin:0 auto 24px;">
    <i class="fa-solid fa-chevron-right"></i>
    <span>Edit Profile</span>
</div>

<div class="page-layout">

    <!-- LEFT: AVATAR + SIDE NAV -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <div class="glass-card avatar-panel">
            <div class="avatar-big"><?= $initials ?></div>
            <div class="avatar-name"><?= htmlspecialchars($name ?? 'Customer') ?></div>
            <div class="avatar-id"><i class="fa-solid fa-id-card" style="color:#4cc9f0;"></i> <?= htmlspecialchars($customer_id) ?></div>

            <div class="avatar-divider"></div>

            <div class="side-nav">
                <a href="#personal" class="active">
                    <i class="fa-solid fa-user"></i> Personal Info
                </a>
                <a href="#password">
                    <i class="fa-solid fa-lock"></i> Change Password
                </a>
                <a href="profile2.php">
                    <i class="fa-solid fa-arrow-left"></i> Back to Profile
                </a>
            </div>
        </div>

    </div>

    <!-- RIGHT: FORM -->
    <div>
        <div class="glass-card">

            <div class="card-title">
                <i class="fa-solid fa-pen-to-square"></i>
                Edit Profile
            </div>
            <div class="card-sub">Update your personal information below. Changes are saved immediately.</div>

            <?php if($success_msg): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($success_msg) ?>
            </div>
            <?php endif; ?>

            <?php if($error_msg): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error_msg) ?>
            </div>
            <?php endif; ?>

            <form method="post" id="editForm">

                <!-- PERSONAL INFO -->
                <div class="section-sep" id="personal">
                    <i class="fa-solid fa-circle-user"></i> Personal Information
                </div>

                <div class="field-grid">

                    <div class="field field-full">
                        <label>Full Name</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-user"></i>
                            <input type="text" name="name"
                                value="<?= htmlspecialchars($name ?? '') ?>"
                                placeholder="Your full name" required>
                        </div>
                    </div>

                    <div class="field">
                        <label>Customer ID</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-id-card"></i>
                            <input type="text" value="<?= htmlspecialchars($customer_id) ?>" readonly>
                        </div>
                    </div>

                    <div class="field">
                        <label>Email Address</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-envelope"></i>
                            <input type="email" name="email"
                                value="<?= htmlspecialchars($email ?? '') ?>"
                                placeholder="your@email.com" required>
                        </div>
                    </div>

                    <div class="field">
                        <label>Phone Number</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-phone"></i>
                            <input type="text" name="phone_no"
                                value="<?= htmlspecialchars($phone ?? '') ?>"
                                placeholder="+91 00000 00000" required>
                        </div>
                    </div>

                    <div class="field field-full">
                        <label>Address</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-location-dot"></i>
                            <input type="text" name="address"
                                value="<?= htmlspecialchars($address ?? '') ?>"
                                placeholder="Your full address">
                        </div>
                    </div>

                </div>

                <!-- CHANGE PASSWORD -->
                <div class="section-sep" id="password">
                    <i class="fa-solid fa-lock"></i> Change Password
                </div>
                <p style="font-size:13px;color:#64748b;margin-bottom:18px;">
                    Leave blank to keep your current password.
                </p>

                <div class="field-grid">

                    <div class="field field-full">
                        <label>Current Password</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-lock"></i>
                            <input type="password" name="cur_pwd"
                                id="curPwd" placeholder="Enter current password">
                            <button type="button" class="eye-btn" onclick="togglePwd('curPwd',this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="field">
                        <label>New Password</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-key"></i>
                            <input type="password" name="new_pwd"
                                id="newPwd" placeholder="Enter new password"
                                oninput="checkStrength(this.value)">
                            <button type="button" class="eye-btn" onclick="togglePwd('newPwd',this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="pwd-strength">
                            <div class="pwd-bar"><div class="pwd-fill" id="pwdFill"></div></div>
                            <div class="pwd-text" id="pwdText"></div>
                        </div>
                    </div>

                    <div class="field">
                        <label>Confirm New Password</label>
                        <div class="input-wrap">
                            <i class="fi fa-solid fa-key"></i>
                            <input type="password" name="confirm_pwd"
                                id="confirmPwd" placeholder="Re-enter new password"
                                oninput="checkMatch()">
                            <button type="button" class="eye-btn" onclick="togglePwd('confirmPwd',this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="pwd-text" id="matchText" style="margin-top:6px;"></div>
                    </div>

                </div>

                <div class="btn-row">
                    <button type="submit" name="update_profile" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                </div>

            </form>

            <!-- DANGER ZONE -->
            <div class="danger-zone">
                <div class="dz-title"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</div>
                <div class="dz-desc">Logging out will end your current session. All unsaved changes will be lost.</div>
                <a href="logout_handler.php" class="btn-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

        </div>
    </div>

</div>

<script>
function togglePwd(id, btn){
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if(input.type === 'password'){
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}

function checkStrength(val){
    const fill = document.getElementById('pwdFill');
    const text = document.getElementById('pwdText');
    let score = 0;
    if(val.length >= 6)  score++;
    if(val.length >= 10) score++;
    if(/[A-Z]/.test(val)) score++;
    if(/[0-9]/.test(val)) score++;
    if(/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { pct:'20%', color:'#ef4444', label:'Very weak' },
        { pct:'40%', color:'#f97316', label:'Weak' },
        { pct:'60%', color:'#eab308', label:'Fair' },
        { pct:'80%', color:'#22c55e', label:'Strong' },
        { pct:'100%',color:'#4cc9f0', label:'Very strong' },
    ];
    const l = levels[Math.max(0, score-1)];
    if(val.length === 0){
        fill.style.width = '0'; text.textContent = ''; return;
    }
    fill.style.width   = l.pct;
    fill.style.background = l.color;
    text.textContent   = l.label;
    text.style.color   = l.color;
}

function checkMatch(){
    const np = document.getElementById('newPwd').value;
    const cp = document.getElementById('confirmPwd').value;
    const mt = document.getElementById('matchText');
    if(cp.length === 0){ mt.textContent = ''; return; }
    if(np === cp){
        mt.textContent = '✓ Passwords match';
        mt.style.color = '#86efac';
    } else {
        mt.textContent = '✗ Passwords do not match';
        mt.style.color = '#fca5a5';
    }
}

/* prevent submit if passwords don't match */
document.getElementById('editForm').addEventListener('submit', function(e){
    const np = document.getElementById('newPwd').value;
    const cp = document.getElementById('confirmPwd').value;
    if(np && np !== cp){
        e.preventDefault();
        document.getElementById('matchText').textContent = '✗ Passwords do not match';
        document.getElementById('matchText').style.color = '#fca5a5';
        document.getElementById('confirmPwd').scrollIntoView({behavior:'smooth'});
    }
});

/* smooth scroll for side nav */
document.querySelectorAll('.side-nav a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e){
        e.preventDefault();
        document.querySelector(this.getAttribute('href'))
                ?.scrollIntoView({behavior:'smooth'});
        document.querySelectorAll('.side-nav a').forEach(x => x.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

</body>
</html>
