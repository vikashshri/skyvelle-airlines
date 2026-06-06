<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Skyvelle Airlines — Fly Beyond</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<style>

:root{
  --ink:     #060d1f;
  --sky:     #0a1628;
  --gold:    #c9a84c;
  --gold2:   #e8c97a;
  --ice:     #d6e8f7;
  --mist:    rgba(214,232,247,0.08);
  --glass:   rgba(255,255,255,0.055);
  --border:  rgba(201,168,76,0.18);
  --ff-disp: 'Cormorant Garamond', Georgia, serif;
  --ff-body: 'DM Sans', sans-serif;
}

*{ margin:0; padding:0; box-sizing:border-box; }

html{ scroll-behavior:smooth; }

body{
  background:var(--ink);
  color:white;
  font-family:var(--ff-body);
  overflow-x:hidden;
}

/* ══════════════════════════
   NAVBAR
══════════════════════════ */

nav{
  position:fixed;
  top:0; left:0; right:0;
  z-index:100;
  padding:20px 60px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  transition:0.4s;
}

nav.scrolled{
  background:rgba(6,13,31,0.92);
  backdrop-filter:blur(20px);
  border-bottom:1px solid var(--border);
  padding:14px 60px;
}

.nav-logo{
  font-family:var(--ff-disp);
  font-size:35px;
  font-weight:600;
  letter-spacing:1px;
  color:white;
  text-decoration:none;
  display:flex;
  align-items:center;
  gap:10px;
}

.nav-logo .plane-icon{
  color:var(--gold);
  font-size:22px;
  transform:rotate(-10deg);
  display:inline-block;
  transition:0.4s;
}

.nav-logo:hover .plane-icon{ transform:rotate(-10deg) translateX(6px); }

.nav-links{
  display:flex;
  gap:8px;
  align-items:center;
}

.nav-links a{
  color:rgba(255,255,255,0.7);
  text-decoration:none;
  font-size:14px;
  font-weight:400;
  padding:8px 16px;
  border-radius:8px;
  transition:0.2s;
  letter-spacing:0.3px;
}

.nav-links a:hover{ color:white; background:var(--mist); }

.nav-cta{
  background:var(--gold) !important;
  color:var(--ink) !important;
  font-weight:600 !important;
  border-radius:10px !important;
}

.nav-cta:hover{ background:var(--gold2) !important; transform:translateY(-1px); box-shadow:0 4px 20px rgba(201,168,76,0.4); }

/* ══════════════════════════
   HERO
══════════════════════════ */

.hero{
  min-height:85vh;
  position:relative;
  display:flex;
  flex-direction:column;
  justify-content:center;
  overflow:hidden;
  min-height:90vh;
  padding-bottom:120px;
}

/* layered sky background */
.hero-bg{
  position:absolute;
  inset:0;
  background:
    radial-gradient(ellipse 80% 60% at 60% 40%, rgba(13,40,90,0.9) 0%, transparent 70%),
    radial-gradient(ellipse 60% 80% at 20% 80%, rgba(10,22,50,0.8) 0%, transparent 60%),
    linear-gradient(160deg, #060d1f 0%, #0d1e3d 40%, #142850 70%, #0a1628 100%);
}

/* star particles */
.stars{
  position:absolute;
  inset:0;
  background-image:
    radial-gradient(1px 1px at 15% 20%, rgba(255,255,255,0.6) 0%, transparent 100%),
    radial-gradient(1px 1px at 35% 10%, rgba(255,255,255,0.4) 0%, transparent 100%),
    radial-gradient(1.5px 1.5px at 55% 25%, rgba(255,255,255,0.5) 0%, transparent 100%),
    radial-gradient(1px 1px at 75% 8%, rgba(255,255,255,0.3) 0%, transparent 100%),
    radial-gradient(1px 1px at 90% 18%, rgba(255,255,255,0.5) 0%, transparent 100%),
    radial-gradient(1px 1px at 10% 55%, rgba(255,255,255,0.3) 0%, transparent 100%),
    radial-gradient(1px 1px at 80% 60%, rgba(255,255,255,0.25) 0%, transparent 100%),
    radial-gradient(1px 1px at 45% 70%, rgba(255,255,255,0.2) 0%, transparent 100%);
}

/* horizon glow */
.hero-glow{
  position:absolute;
  bottom:0; left:0; right:0;
  height:350px;
  background:linear-gradient(to top,
    rgba(201,168,76,0.12) 0%,
    rgba(100,160,220,0.08) 40%,
    transparent 100%);
}

/* plane silhouette */
.hero-plane{
  position:absolute;
  right:-5%;
  top:50%;
  transform:translateY(-50%);
  width:65%;
  max-width:820px;
  opacity:0.18;
  filter:blur(0.5px);
  animation:planedrift 12s ease-in-out infinite;
}

@keyframes planedrift{
  0%,100%{ transform:translateY(-50%) translateX(0); }
  50%{ transform:translateY(-52%) translateX(-15px); }
}

.hero-content{
  position:relative;
  z-index:2;
  max-width:900px;
  width:100%;
  margin:0 auto;
  padding-top:120px;
  text-align:center;
  display:flex;
  flex-direction:column;
  align-items:center;
}

.hero-eyebrow{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-size:12px;
  font-weight:500;
  letter-spacing:3px;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:24px;
  opacity:0;
  animation:fadein 0.8s 0.2s forwards;
}

.hero-eyebrow::before{
  content:'';
  width:30px; height:1px;
  background:var(--gold);
}

.hero-h1{
  font-family:var(--ff-disp);
  font-size:clamp(52px,7vw,75px);
  font-weight:300;
  line-height:0.95;
  letter-spacing:-1px;
  margin-bottom:28px;
  opacity:0;
  animation:fadein 0.8s 0.4s forwards;
}

.hero-h1 em{
  font-style:italic;
  color:var(--gold);
}

.hero-sub{
  font-size:16px;
  font-weight:300;
  line-height:1.7;
  color:rgba(214,232,247,0.75);
  text-align:center;
  max-width:650px;
  margin-bottom:48px;
  opacity:0;
  animation:fadein 0.8s 0.6s forwards;
}

.hero-actions{
  display:flex;
  justify-content:center;
  gap:16px;
  flex-wrap:wrap;
  opacity:0;
  animation:fadein 0.8s 0.8s forwards;
}

.btn-primary{
  display:inline-flex;
  align-items:center;
  gap:10px;
  background:var(--gold);
  color:var(--ink);
  text-decoration:none;
  font-weight:600;
  font-size:15px;
  padding:16px 32px;
  border-radius:12px;
  transition:0.3s;
  letter-spacing:0.3px;
}

.btn-primary:hover{
  background:var(--gold2);
  transform:translateY(-3px);
  box-shadow:0 12px 30px rgba(201,168,76,0.4);
}

.btn-secondary{
  display:inline-flex;
  align-items:center;
  gap:10px;
  background:transparent;
  color:white;
  text-decoration:none;
  font-weight:400;
  font-size:15px;
  padding:16px 32px;
  border-radius:12px;
  border:1px solid rgba(255,255,255,0.2);
  transition:0.3s;
}

.btn-secondary:hover{
  background:var(--mist);
  border-color:rgba(255,255,255,0.4);
  transform:translateY(-2px);
}

/* STAT STRIP */
.hero-stats{
  position:absolute;
  bottom:0; left:0; right:0;
  z-index:3;
  display:grid;
  grid-template-columns:repeat(4,1fr);
  border-top:1px solid var(--border);
  background:rgba(6,13,31,0.7);
  backdrop-filter:blur(12px);
  opacity:0;
  animation:fadein 0.8s 1s forwards;
}

.stat-item{
  padding:28px 36px;
  border-right:1px solid var(--border);
  transition:0.3s;
}

.stat-item:last-child{ border-right:none; }

.stat-item:hover{ background:var(--mist); }

.stat-num{
  font-family:var(--ff-disp);
  font-size:38px;
  font-weight:300;
  color:var(--gold);
  line-height:1;
  margin-bottom:4px;
}

.stat-lbl{
  font-size:12px;
  color:rgba(214,232,247,0.5);
  letter-spacing:1px;
  text-transform:uppercase;
}

@keyframes fadein{
  from{ opacity:0; transform:translateY(18px); }
  to{ opacity:1; transform:translateY(0); }
}

/* ══════════════════════════
   SEARCH SECTION
══════════════════════════ */

.search-section{
  background:var(--sky);
  padding:80px 60px;
  position:relative;
}

.search-section::before{
  content:'';
  position:absolute;
  top:0; left:60px; right:60px;
  height:1px;
  background:linear-gradient(90deg, transparent, var(--border), transparent);
}

.section-label{
  font-size:11px;
  letter-spacing:4px;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:12px;
  display:flex;
  align-items:center;
  gap:10px;
}

.section-label::after{
  content:'';
  flex:1;
  max-width:60px;
  height:1px;
  background:var(--gold);
  opacity:0.4;
}

.section-h2{
  font-family:var(--ff-disp);
  font-size:clamp(32px,4vw,52px);
  font-weight:300;
  margin-bottom:44px;
  line-height:1.1;
}

.section-h2 em{ font-style:italic; color:var(--gold); }

/* SEARCH FORM */
.search-card{
  background:rgba(255,255,255,0.04);
  border:1px solid var(--border);
  border-radius:20px;
  padding:36px 40px;
  max-width:980px;
}

.search-grid{
  display:grid;
  grid-template-columns:1fr 1fr 200px 180px;
  gap:0;
  margin-bottom:24px;
  border:1px solid rgba(255,255,255,0.08);
  border-radius:14px;
  overflow:hidden;
}

.search-field{
  padding:18px 22px;
  border-right:1px solid rgba(255,255,255,0.08);
  background:rgba(255,255,255,0.03);
  transition:0.2s;
  position:relative;
}

.search-field:last-child{ border-right:none; }
.search-field:hover{ background:rgba(255,255,255,0.06); }
.search-field:focus-within{ background:rgba(201,168,76,0.06); }

.sf-label{
  font-size:10px;
  letter-spacing:2px;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:8px;
  display:flex;
  align-items:center;
  gap:6px;
}

.sf-label i{ font-size:10px; }

.search-field input,
.search-field select{
  width:100%;
  background:transparent;
  border:none;
  outline:none;
  color:white;
  font-family:var(--ff-body);
  font-size:16px;
  font-weight:400;
}

.search-field input::placeholder{ color:rgba(255,255,255,0.3); }
.search-field select option{ background:#0d1e3d; color:white; }

input[type="date"]::-webkit-calendar-picker-indicator{ filter:invert(0.6); cursor:pointer; }

.search-row2{
  display:flex;
  gap:16px;
  align-items:flex-end;
}

.search-field-sm{
  flex:1;
  background:rgba(255,255,255,0.03);
  border:1px solid rgba(255,255,255,0.08);
  border-radius:12px;
  padding:14px 18px;
  transition:0.2s;
}

.search-field-sm:focus-within{ background:rgba(201,168,76,0.06); border-color:rgba(201,168,76,0.3); }

.search-field-sm input,
.search-field-sm select{
  width:100%;
  background:transparent;
  border:none;
  outline:none;
  color:white;
  font-family:var(--ff-body);
  font-size:15px;
}

.search-field-sm input::placeholder{ color:rgba(255,255,255,0.3); }
.search-field-sm select option{ background:#0d1e3d; }

.search-submit{
  background:var(--gold);
  color:var(--ink);
  border:none;
  padding:16px 36px;
  border-radius:12px;
  font-family:var(--ff-body);
  font-size:15px;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
  display:flex;
  align-items:center;
  gap:10px;
  white-space:nowrap;
}

.search-submit:hover{
  background:var(--gold2);
  transform:translateY(-2px);
  box-shadow:0 8px 24px rgba(201,168,76,0.4);
}

/* ══════════════════════════
   DESTINATIONS
══════════════════════════ */

.destinations-section{
  padding:100px 60px;
  background:var(--ink);
  position:relative;
}

.destinations-grid{
  display:grid;
  grid-template-columns:2fr 1fr 1fr;
  grid-template-rows:280px 240px;
  gap:16px;
  margin-top:48px;
}

.dest-card{
  position:relative;
  border-radius:18px;
  overflow:hidden;
  cursor:pointer;
  group:true;
}

.dest-card:first-child{ grid-row:1/3; }

.dest-img{
  width:100%;
  height:100%;
  object-fit:cover;
  transition:0.6s;
  filter:brightness(0.7);
}

.dest-card:hover .dest-img{
  transform:scale(1.06);
  filter:brightness(0.55);
}

.dest-overlay{
  position:absolute;
  inset:0;
  background:linear-gradient(to top, rgba(6,13,31,0.85) 0%, transparent 60%);
  display:flex;
  flex-direction:column;
  justify-content:flex-end;
  padding:28px;
  transition:0.3s;
}

.dest-tag{
  font-size:10px;
  letter-spacing:2px;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:6px;
}

.dest-city{
  font-family:var(--ff-disp);
  font-size:clamp(22px,2.5vw,34px);
  font-weight:300;
  margin-bottom:4px;
}

.dest-price{
  font-size:13px;
  color:rgba(255,255,255,0.6);
  display:flex;
  align-items:center;
  gap:6px;
}

.dest-price strong{ color:var(--gold2); font-size:16px; }

.dest-arrow{
  position:absolute;
  top:20px; right:20px;
  width:36px; height:36px;
  border-radius:50%;
  background:rgba(201,168,76,0.2);
  border:1px solid rgba(201,168,76,0.3);
  display:flex;
  align-items:center;
  justify-content:center;
  opacity:0;
  transform:scale(0.8);
  transition:0.3s;
  color:var(--gold);
  font-size:12px;
}

.dest-card:hover .dest-arrow{
  opacity:1;
  transform:scale(1);
}

/* ══════════════════════════
   FEATURES / WHY US
══════════════════════════ */

.features-section{
  padding:100px 60px;
  background:linear-gradient(160deg, #0a1628 0%, #060d1f 100%);
  position:relative;
  overflow:hidden;
}

.features-section::before{
  content:'';
  position:absolute;
  top:-200px; right:-200px;
  width:600px; height:600px;
  border-radius:50%;
  background:radial-gradient(circle, rgba(201,168,76,0.04) 0%, transparent 70%);
}

.features-layout{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:80px;
  align-items:center;
  margin-top:60px;
}

.features-visual{
  position:relative;
}

.fv-main{
  border-radius:24px;
  overflow:hidden;
  aspect-ratio:4/3;
  position:relative;
}

.fv-main img{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(0.8);
}

.fv-main::after{
  content:'';
  position:absolute;
  inset:0;
  border:1px solid var(--border);
  border-radius:24px;
  pointer-events:none;
}

.fv-badge{
  position:absolute;
  bottom:-20px; right:-20px;
  background:var(--gold);
  color:var(--ink);
  border-radius:16px;
  padding:20px 24px;
  text-align:center;
  box-shadow:0 12px 30px rgba(201,168,76,0.35);
}

.fv-badge .num{
  font-family:var(--ff-disp);
  font-size:36px;
  font-weight:600;
  line-height:1;
}

.fv-badge .txt{
  font-size:11px;
  font-weight:500;
  letter-spacing:1px;
  text-transform:uppercase;
  opacity:0.8;
}

.features-list{
  display:flex;
  flex-direction:column;
  gap:32px;
}

.feature-item{
  display:flex;
  gap:20px;
  padding:24px;
  border-radius:16px;
  border:1px solid transparent;
  transition:0.3s;
  cursor:default;
}

.feature-item:hover{
  background:var(--mist);
  border-color:var(--border);
}

.fi-icon{
  width:48px; height:48px;
  border-radius:12px;
  background:rgba(201,168,76,0.1);
  border:1px solid rgba(201,168,76,0.2);
  display:flex;
  align-items:center;
  justify-content:center;
  color:var(--gold);
  font-size:18px;
  flex-shrink:0;
  transition:0.3s;
}

.feature-item:hover .fi-icon{
  background:var(--gold);
  color:var(--ink);
  transform:rotate(-6deg);
}

.fi-title{
  font-size:17px;
  font-weight:600;
  margin-bottom:6px;
}

.fi-desc{
  font-size:14px;
  color:rgba(214,232,247,0.55);
  line-height:1.65;
}

/* ══════════════════════════
   QUICK ACTIONS
══════════════════════════ */

.actions-section{
  padding:100px 60px;
  background:var(--ink);
}

.actions-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:20px;
  margin-top:48px;
}

.action-card{
  background:var(--glass);
  border:1px solid var(--border);
  border-radius:20px;
  padding:36px 28px;
  text-decoration:none;
  color:white;
  transition:0.35s;
  position:relative;
  overflow:hidden;
}

.action-card::before{
  content:'';
  position:absolute;
  top:0; left:0; right:0;
  height:2px;
  background:linear-gradient(90deg, transparent, var(--gold), transparent);
  opacity:0;
  transition:0.3s;
}

.action-card:hover{
  transform:translateY(-8px);
  background:rgba(255,255,255,0.08);
  border-color:rgba(201,168,76,0.35);
  box-shadow:0 20px 50px rgba(0,0,0,0.4);
}

.action-card:hover::before{ opacity:1; }

.ac-icon{
  width:52px; height:52px;
  border-radius:14px;
  background:rgba(201,168,76,0.1);
  border:1px solid rgba(201,168,76,0.15);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:22px;
  color:var(--gold);
  margin-bottom:24px;
  transition:0.3s;
}

.action-card:hover .ac-icon{
  background:var(--gold);
  color:var(--ink);
  transform:scale(1.1) rotate(-5deg);
}

.ac-title{
  font-size:18px;
  font-weight:600;
  margin-bottom:10px;
}

.ac-desc{
  font-size:13px;
  color:rgba(214,232,247,0.5);
  line-height:1.65;
}

.ac-arrow{
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-size:12px;
  color:var(--gold);
  margin-top:20px;
  opacity:0;
  transform:translateX(-6px);
  transition:0.3s;
}

.action-card:hover .ac-arrow{
  opacity:1;
  transform:translateX(0);
}

/* ══════════════════════════
   TESTIMONIAL / CTA BAND
══════════════════════════ */

.cta-band{
  margin:0 60px 100px;
  border-radius:28px;
  background:linear-gradient(135deg,#1a2d5a 0%,#0d1e3d 50%,#1a2d5a 100%);
  border:1px solid var(--border);
  padding:80px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:40px;
  position:relative;
  overflow:hidden;
}

.cta-band::before{
  content:'✈';
  position:absolute;
  right:80px; top:50%;
  transform:translateY(-50%);
  font-size:220px;
  opacity:0.04;
  line-height:1;
  pointer-events:none;
}

.cta-text .label{
  font-size:11px;
  letter-spacing:3px;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:14px;
  display:block;
}

.cta-text h2{
  font-family:var(--ff-disp);
  font-size:clamp(30px,4vw,52px);
  font-weight:300;
  line-height:1.1;
  margin-bottom:16px;
}

.cta-text p{
  font-size:15px;
  color:rgba(214,232,247,0.6);
  max-width:460px;
  line-height:1.7;
}

.cta-actions{ display:flex; flex-direction:column; gap:14px; flex-shrink:0; }

/* ══════════════════════════
   FOOTER
══════════════════════════ */

footer{
  background:#030810;
  border-top:1px solid var(--border);
  padding:60px;
}

.footer-grid{
  display:grid;
  grid-template-columns:2fr 1fr 1fr 1fr;
  gap:60px;
  margin-bottom:50px;
}

.footer-brand .logo{
  font-family:var(--ff-disp);
  font-size:24px;
  font-weight:600;
  color:white;
  margin-bottom:16px;
  display:flex;
  align-items:center;
  gap:8px;
}

.footer-brand .logo i{ color:var(--gold); }

.footer-brand p{
  font-size:13px;
  color:rgba(214,232,247,0.4);
  line-height:1.7;
  margin-bottom:24px;
  max-width:280px;
}

.social-links{ display:flex; gap:10px; }

.social-links a{
  width:36px; height:36px;
  border-radius:8px;
  background:var(--mist);
  border:1px solid var(--border);
  display:flex;
  align-items:center;
  justify-content:center;
  color:rgba(255,255,255,0.5);
  text-decoration:none;
  font-size:14px;
  transition:0.2s;
}

.social-links a:hover{ background:var(--gold); color:var(--ink); border-color:var(--gold); }

.footer-col h4{
  font-size:12px;
  letter-spacing:2px;
  text-transform:uppercase;
  color:var(--gold);
  margin-bottom:20px;
}

.footer-col a{
  display:block;
  font-size:14px;
  color:rgba(214,232,247,0.45);
  text-decoration:none;
  margin-bottom:10px;
  transition:0.2s;
}
.live-section{

	background:rgba(255,255,255,0.05);

	padding:40px;

	margin:40px 60px;
}

.live-section h2{

	font-family:var(--ff-disp);

	font-size:38px;

	font-weight:300;

	margin-bottom:15px;

	color:white;
}

.live-section h2 i{

	color:var(--gold);

	margin-right:10px;
}

.live-section p{

	line-height:1.9;

	font-size:15px;

	color:rgba(214,232,247,0.75);

	max-width:700px;
}
.footer-col a:hover{ color:white; transform:translateX(4px); }

.footer-bottom{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding-top:30px;
  border-top:1px solid rgba(255,255,255,0.06);
  font-size:13px;
  color:rgba(214,232,247,0.3);
}

/* ══════════════════════════
   RESPONSIVE
══════════════════════════ */

@media(max-width:1100px){
  nav, .search-section, .destinations-section,
  .features-section, .actions-section{ padding-left:32px; padding-right:32px; }
  nav.scrolled{ padding-left:32px; padding-right:32px; }
  .cta-band{ margin-left:32px; margin-right:32px; padding:50px 40px; }
  footer{ padding:50px 32px; }
  .actions-grid{ grid-template-columns:repeat(2,1fr); }
  .footer-grid{ grid-template-columns:1fr 1fr; gap:40px; }
}

@media(max-width:768px){
  nav{ padding:16px 24px; }
  nav.scrolled{ padding:12px 24px; }
  .nav-links a:not(.nav-cta){ display:none; }
  .hero-content{ padding:0 24px; padding-top:110px; }
  .hero-stats{ grid-template-columns:1fr 1fr; }
  .search-grid{ grid-template-columns:1fr; }
  .search-field{ border-right:none; border-bottom:1px solid rgba(255,255,255,0.08); }
  .destinations-grid{ grid-template-columns:1fr; grid-template-rows:auto; }
  .dest-card:first-child{ grid-row:auto; }
  .features-layout{ grid-template-columns:1fr; gap:40px; }
  .actions-grid{ grid-template-columns:1fr; }
  .cta-band{ flex-direction:column; text-align:center; }
  .footer-grid{ grid-template-columns:1fr; gap:32px; }
  .footer-bottom{ flex-direction:column; gap:10px; text-align:center; }
}

</style>
</head>
<body>

<!-- NAVBAR -->
<nav id="navbar">
  <a href="#" class="nav-logo">
    <span class="plane-icon"><i class="fa-solid fa-plane"></i></span>
    Skyvelle
  </a>
  <div class="nav-links">
    <a href="#destinations">Destinations</a>
    <a href="#features">Why Us</a>
    <a href="profile2.php">Profile</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="stars"></div>
  <div class="hero-glow"></div>

  <!-- plane SVG silhouette -->
  <svg class="hero-plane" viewBox="0 0 900 400" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M50 200 L700 140 L860 185 L700 200 L50 200Z" fill="white"/>
    <path d="M350 140 L500 60 L560 80 L440 155Z" fill="white"/>
    <path d="M350 200 L460 280 L510 270 L420 200Z" fill="white"/>
    <path d="M700 200 L760 210 L780 200 L760 192Z" fill="white"/>
    <ellipse cx="830" cy="185" rx="35" ry="20" fill="white"/>
    <circle cx="835" cy="185" r="12" fill="#0a1628"/>
  </svg>

  <div class="hero-content">
    <div class="hero-eyebrow">
      <i class=""></i> Premium Airline Experience
    </div>
    <h1 class="hero-h1">
      Where <em>Destination</em><br>
      meet Dreams
    </h1>
    <p class="hero-sub">
      Discover seamless journeys with Skyvelle Airlines <br>
      Smart booking, real-time tracking, and world class comfort
      from takeoff to touchdown.
    </p>
    <div class="hero-actions">
      <a href="book_tickets.php" class="btn-primary">
         <i class="fa-solid fa-plane"></i> Start Flying
      </a>
      <a href="tracker.php" class="btn-secondary">
        <i class="fa-solid fa-map-location-dot"></i>
        Track Flight
      </a>
    </div>
  </div>

  <div class="hero-stats">
    <div class="stat-item">
      <div class="stat-num">180+</div>
      <div class="stat-lbl">Destinations</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">2.4M</div>
      <div class="stat-lbl">Happy Passengers</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">98%</div>
      <div class="stat-lbl">On-time Flights</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">24/7</div>
      <div class="stat-lbl">Customer Support</div>
    </div>
  </div>
</section>
<div class="live-section">

	<h2>
		<i class="fa-solid fa-plane-circle-check"></i>
		Live Flight Updates
	</h2>

	<p>
		Stay updated with real-time flight schedules, departure alerts,
		boarding notifications and arrival timings directly from your dashboard.
	</p>

</div>
<!-- QUICK ACTIONS -->
<section id="quick-actions" class="actions-section">  
  <div class="section-label"><i class="fa-solid fa-grid-2"></i> Quick Access</div>
  <h2 class="section-h2">Everything you need,<br><em>right here</em></h2>

  <div class="actions-grid">
    <a href="book_tickets.php" class="action-card">
      <div class="ac-icon"><i class="fa-solid fa-ticket"></i></div>
      <div class="ac-title">Book Tickets</div>
      <div class="ac-desc">Search and reserve flights instantly with our intelligent booking engine.</div>
      <div class="ac-arrow">Get started <i class="fa-solid fa-arrow-right"></i></div>
    </a>
    <a href="view_booked_tickets.php" class="action-card">
      <div class="ac-icon"><i class="fa-solid fa-plane-departure"></i></div>
      <div class="ac-title">My Trips</div>
      <div class="ac-desc">View all your upcoming and past reservations in one place.</div>
      <div class="ac-arrow">View trips <i class="fa-solid fa-arrow-right"></i></div>
    </a>
    <a href="cancel_booked_tickets.php" class="action-card">
      <div class="ac-icon"><i class="fa-solid fa-ban"></i></div>
      <div class="ac-title">Cancel Booking</div>
      <div class="ac-desc">Need to change plans? Manage and cancel reservations hassle-free.</div>
      <div class="ac-arrow">Manage <i class="fa-solid fa-arrow-right"></i></div>
    </a>
    <a href="profile2.php" class="action-card">
      <div class="ac-icon"><i class="fa-solid fa-user-circle"></i></div>
      <div class="ac-title">My Profile</div>
      <div class="ac-desc">Update your details, view FF status and manage travel preferences.</div>
      <div class="ac-arrow">Open profile <i class="fa-solid fa-arrow-right"></i></div>
    </a>
  </div>
</section>
<!-- DESTINATIONS -->
<section class="destinations-section" id="destinations">
  <div class="section-label"><i class="fa-solid fa-earth-asia"></i> Popular Routes</div>
  <h2 class="section-h2">Top <em>Destinations</em><br>from Bangalore</h2>

  <div class="destinations-grid">

    <div class="dest-card">
      <img class="dest-img" src="https://images.unsplash.com/photo-1529253355930-ddbe423a2ac7?w=800&auto=format&fit=crop" alt="Mumbai">
      <div class="dest-overlay">
        <div class="dest-tag">Most Popular</div>
        <div class="dest-city">Mumbai</div>
        <div class="dest-price">From <strong>₹3,499</strong></div>
      </div>
      <div class="dest-arrow"><i class="fa-solid fa-arrow-right"></i></div>
    </div>

    <div class="dest-card">
      <img class="dest-img" src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=600&auto=format&fit=crop" alt="Chennai">
      <div class="dest-overlay">
        <div class="dest-tag">Quick Hop</div>
        <div class="dest-city">Chennai</div>
        <div class="dest-price">From <strong>₹2,199</strong></div>
      </div>
      <div class="dest-arrow"><i class="fa-solid fa-arrow-right"></i></div>
    </div>

    <div class="dest-card">
      <img class="dest-img" src="https://i.pinimg.com/736x/93/71/51/9371518a515df4c546e196173b9937c6.jpg" alt="Hyderabad">
      <div class="dest-overlay">
        <div class="dest-tag">Business Hub</div>
        <div class="dest-city">Hyderabad</div>
        <div class="dest-price">From <strong>₹2,799</strong></div>
      </div>
      <div class="dest-arrow"><i class="fa-solid fa-arrow-right"></i></div>
    </div>

    <div class="dest-card">
      <img class="dest-img" src="https://i.pinimg.com/1200x/6f/54/97/6f54978f7c061c77b7e27f8829874b74.jpg" alt="Mangalore">
      <div class="dest-overlay">
        <div class="dest-tag">Coastal Escape</div>
        <div class="dest-city">Mangalore</div>
        <div class="dest-price">From <strong>₹1,899</strong></div>
      </div>
      <div class="dest-arrow"><i class="fa-solid fa-arrow-right"></i></div>
    </div>

    <div class="dest-card">
      <img class="dest-img" src="https://images.unsplash.com/photo-1599661046289-e31897846e41?w=600&auto=format&fit=crop" alt="Mysore">
      <div class="dest-overlay">
        <div class="dest-tag">Heritage City</div>
        <div class="dest-city">Mysore</div>
        <div class="dest-price">From <strong>₹1,499</strong></div>
      </div>
      <div class="dest-arrow"><i class="fa-solid fa-arrow-right"></i></div>
    </div>

  </div>
</section>

<!-- WHY SKYVELLE -->
<section class="features-section" id="features">
  <div class="section-label"><i class="fa-solid fa-shield-halved"></i> Why Choose Us</div>
  <h2 class="section-h2">Flying smarter with<br><em>Skyvelle</em></h2>

  <div class="features-layout">
    <div class="features-visual">
      <div class="fv-main">
        <img src="https://images.unsplash.com/photo-1570710891163-6d3b5c47248b?w=800&auto=format&fit=crop" alt="Cabin">
      </div>
      <div class="fv-badge">
        <div class="num">98%</div>
        <div class="txt">Satisfaction Rate</div>
      </div>
    </div>

    <div class="features-list">
      <div class="feature-item">
        <div class="fi-icon"><i class="fa-solid fa-bolt"></i></div>
        <div>
          <div class="fi-title">Instant Booking</div>
          <div class="fi-desc">Book your seat in under 2 minutes with our smart search and one-click checkout system.</div>
        </div>
      </div>
      <div class="feature-item">
        <div class="fi-icon"><i class="fa-solid fa-map-location-dot"></i></div>
        <div>
          <div class="fi-title">Real-Time Tracking</div>
          <div class="fi-desc">Follow your flight live — gate updates, delays, and boarding notifications right on your dashboard.</div>
        </div>
      </div>
      <div class="feature-item">
        <div class="fi-icon"><i class="fa-solid fa-crown"></i></div>
        <div>
          <div class="fi-title">Frequent Flyer Rewards</div>
          <div class="fi-desc">Earn mileage points on every trip. Auto-enrol after your 3rd booking and unlock exclusive perks.</div>
        </div>
      </div>
      <div class="feature-item">
        <div class="fi-icon"><i class="fa-solid fa-chair"></i></div>
        <div>
          <div class="fi-title">Seat Selection</div>
          <div class="fi-desc">Pick your perfect seat from an interactive cabin map before your flight — window, aisle, or extra legroom.</div>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- CTA BAND -->
<div class="cta-band">
  <div class="cta-text">
    <span class="label"><i class="fa-solid fa-crown"></i> Frequent Flyer Programme</span>
    <h2>Earn miles on<br>every journey</h2>
    <p>Book 3 flights and automatically join the Skyvelle Frequent Flyer programme. Accumulate mileage points and unlock premium perks.</p>
  </div>
  <div class="cta-actions">
    <a href="book_tickets.php" class="btn-primary">
      <i class="fa-solid fa-plane"></i> Start Flying
    </a>
    <a href="profile2.php" class="btn-secondary">
      <i class="fa-solid fa-star"></i> View FF Status
    </a>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="logo"><i class="fa-solid fa-plane"></i> Skyvelle</div>
      <p>Premium airline booking and travel management — designed for the modern traveller. Smart, fast, and always on time.</p>
      <div class="social-links">
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
        <a href="#"><i class="fa-brands fa-facebook"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Fly</h4>
      <a href="book_tickets.php">Book a Flight</a>
      <a href="view_booked_tickets.php">My Bookings</a>
      <a href="cancel_booked_tickets.php">Cancel Ticket</a>
      <a href="tracker.php">Flight Tracker</a>
    </div>
    <div class="footer-col">
      <h4>Account</h4>
      <a href="profile2.php">My Profile</a>
      <a href="profile2.php">Frequent Flyer</a>
      <a href="login.php">Login</a>
      <a href="new_user.php">Register</a>
    </div>
    <div class="footer-col">
      <h4>Company</h4>
      <a href="#">About Skyvelle</a>
      <a href="#">Careers</a>
      <a href="#">Contact Us</a>
      <a href="#">Privacy Policy</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2026 Skyvelle Airlines. All rights reserved.</span>
    <span>Made with <i class="fa-solid fa-heart" style="color:var(--gold);"></i> for travellers</span>
  </div>
</footer>

<script>
// Sticky navbar
const nav = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  nav.classList.toggle('scrolled', window.scrollY > 60);
});

// Intersection observer for scroll animations
const observer = new IntersectionObserver((entries) => {
  entries.forEach(el => {
    if(el.isIntersecting){
      el.target.style.opacity = '1';
      el.target.style.transform = 'translateY(0)';
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.dest-card, .feature-item, .action-card').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(30px)';
  el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(el);
});
</script>

</body>
</html>
