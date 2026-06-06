<?php

$conn = mysqli_connect("localhost","root","","airline_reservation");

if(!$conn){
    die("Connection Failed");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Flight Results</title>

<style>

body{
    font-family:Poppins,sans-serif;
    background:#f4f7fb;
    padding:40px;
}

.result-box{

    max-width:800px;
    margin:auto;
}

.flight-card{

    background:white;

    padding:25px;

    border-radius:12px;

    margin-bottom:20px;

    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.flight-card h2{
    color:#0057ff;
}

.flight-card p{
    margin:8px 0;
    font-size:17px;
}

</style>

</head>
<body>

<div class="result-box">

<h1>Available Flights</h1>

<?php

/* SEARCH BY FLIGHT */

if(isset($_GET['flight_no'])){

    $flight_no = $_GET['flight_no'];

    $query = "SELECT * FROM flight_details
              WHERE flight_no='$flight_no'";

    $result = mysqli_query($conn,$query);

    while($row=mysqli_fetch_assoc($result)){

        echo '

        <div class="flight-card">

            <h2>'.$row['flight_no'].'</h2>

            <p><b>From :</b> '.$row['from_city'].'</p>

            <p><b>To :</b> '.$row['to_city'].'</p>

            <p><b>Departure :</b> '.$row['departure_time'].'</p>

            <p><b>Arrival :</b> '.$row['arrival_time'].'</p>

            <p><b>Economy Price :</b> ₹'.$row['price_economy'].'</p>

            <p><b>Business Price :</b> ₹'.$row['price_business'].'</p>

        </div>

        ';
    }
}

/* SEARCH BY ROUTE */

if(isset($_GET['source'])){

    $source = $_GET['source'];

    $destination = $_GET['destination'];

    $query = "SELECT * FROM flight_details
              WHERE from_city='$source'
              AND to_city='$destination'";

    $result = mysqli_query($conn,$query);

    while($row=mysqli_fetch_assoc($result)){

        echo '

        <div class="flight-card">

            <h2>'.$row['flight_no'].'</h2>

            <p><b>From :</b> '.$row['from_city'].'</p>

            <p><b>To :</b> '.$row['to_city'].'</p>

            <p><b>Departure :</b> '.$row['departure_time'].'</p>

            <p><b>Arrival :</b> '.$row['arrival_time'].'</p>

            <p><b>Economy Price :</b> ₹'.$row['price_economy'].'</p>

            <p><b>Business Price :</b> ₹'.$row['price_business'].'</p>

        </div>

        ';
    }
}

?>

</div>

</body>
</html>