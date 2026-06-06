<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard | Skyvelle Airlines</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
	margin:0;
	padding:0;
	box-sizing:border-box;
	font-family:'Poppins',sans-serif;
}

body{

	background:
	linear-gradient(rgba(15,15,20,0.90),
	rgba(15,15,20,0.92)),
	url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');

	background-size:cover;
	background-position:center;
	background-attachment:fixed;

	min-height:100vh;

	padding:30px;

	color:white;
}


.logo{
	display:flex;
	align-items:center;
	gap:12px;
}

.logo h1{
font-family: 'Cormorant Garamond', serif; 

	font-size:37px;
	font-weight:700;
	color:white;
}



/* HERO */

.hero{
display:flex;
flex-direction:column;
justify-content:center;
min-height:320px;
    background:
    linear-gradient(135deg,
    #8d6e75,
    #8F6670,
    #E9D4D8);

    padding:50px;

    border-radius:30px;

    margin-bottom:40px;

}

.hero h1{

	font-size:52px;
	font-family:"Playfair Display",serif;
	margin-bottom:18px;
}

.hero p{

	color:#f3f3f3;

	line-height:1.9;

	max-width:850px;

	font-size:16px;
}

/* DASHBOARD GRID */

.dashboard-grid{

	display:grid;

	grid-template-columns:
	repeat(auto-fit,minmax(300px,1fr));

	gap:25px;
}

/* ADMIN CARD */

.admin-card{

    border:1px solid rgba(255,255,255,0.08);

    border-radius:25px;

    padding:35px;

    text-decoration:none;

    color:white;

    transition:0.3s;

    position:relative;

    overflow:hidden;
}

.admin-card:hover{

	transform:translateY(-8px);

	box-shadow:0 15px 30px rgba(0,0,0,0.35);
}

/* ICON */

.card-icon{

	width:75px;
	height:75px;

	border-radius:22px;

	display:flex;
	align-items:center;
	justify-content:center;

	margin-bottom:25px;

	font-size:32px;

	color:white;
}

/* ICON COLORS */

.blue{
    background:#9c7a82;
}

.green{
    background:#b2949b;
}

.red{
    background:#c29aa4;
}

.purple{
    background:#8d6b73;
}

.orange{
    background:#aa7f88;
}

/* TEXT */

.admin-card h2{

	font-size:24px;

	margin-bottom:12px;
}

.admin-card p{

	color:#f3f3f3;

	line-height:1.8;
}

/* ARROW */

.arrow{

    width:55px;
    height:55px;

    border-radius:50%;

    

    border:1px solid rgba(255,255,255,0.08);

    display:flex;

    align-items:center;
    justify-content:center;

    font-size:18px;

    margin-top:25px;

    transition:0.3s;
}

.admin-card:hover .arrow{

	transform:translateX(5px);
}

/* RESPONSIVE */

@media(max-width:768px){

	.navbar{

		flex-direction:column;
		gap:20px;
	}

	.nav-links{

		flex-wrap:wrap;
		justify-content:center;
	}

	.hero{

		padding:35px 25px;
	}

	.hero h1{

		font-size:38px;
	}
}

</style>

</head>

<body>

<!-- NAVBAR -->


	<div class="logo">

		<h1>✈ Skyvelle Admin</h1>

	</div>
<br><br>
	



<!-- HERO SECTION -->

<div class="hero">

<h1>Control the Skies ✈</h1>
	<p>
Manage flights, aircraft, bookings, and airline operations from one centralized control hub. Monitor activity in real time and keep Skyvelle Airlines running smoothly.
</p>

</div>

<!-- DASHBOARD -->

<section id="quick-actions">

    <div style="margin-bottom:15px;color:#e9d4d8;letter-spacing:2px;">
        <i class="fa-solid fa-grid-2"></i> ADMIN QUICK ACCESS
    </div>

    <h2 style="
        font-size:42px;
        margin-bottom:35px;
        font-family:'Playfair Display',serif;
    ">
        Everything you need,<br>
        <em style="color:#d9b7bf;">right here</em>
    </h2>

    <div class="dashboard-grid">

        <a href="admin_view_booked_tickets.php" class="admin-card">

            <div class="card-icon blue">
                <i class="fa-solid fa-ticket"></i>
            </div>

            <h2>View Bookings</h2>

            <p>
                Monitor all passenger reservations and ticket information.
            </p>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

        </a>

        <a href="add_jet_details.php" class="admin-card" id="dashboard">

            <div class="card-icon green">
                <i class="fa-solid fa-plane"></i>
            </div>

            <h2>Add Aircraft</h2>

            <p>
                Register new aircraft and maintain fleet records.
            </p>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

        </a>

        <a href="deactivate_jet_details.php" class="admin-card">

            <div class="card-icon red">
                <i class="fa-solid fa-ban"></i>
            </div>

            <h2>Deactivate Aircraft</h2>

            <p>
                Suspend aircraft operations and schedules when required.
            </p>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

        </a>

        <a href="add_flight_details.php" class="admin-card">

            <div class="card-icon purple">
                <i class="fa-solid fa-calendar-plus"></i>
            </div>

            <h2>Add Flight</h2>

            <p>
                Create new flight schedules and routes.
            </p>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

        </a>

        <a href="delete_flight_details.php" class="admin-card">

            <div class="card-icon orange">
                <i class="fa-solid fa-trash"></i>
            </div>

            <h2>Delete Flight</h2>

            <p>
                Remove cancelled or outdated flight schedules.
            </p>

            <div class="arrow">
                <i class="fa-solid fa-arrow-right"></i>
            </div>

        </a>

    </div>

</section>

</body>
</html>