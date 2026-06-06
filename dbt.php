<?php

echo "Step 1<br>";

$conn = mysqli_connect("localhost", "root", "", "airline_reservation");

echo "Step 2<br>";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Database Connected";
?>