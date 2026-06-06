<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Ticket Details</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{

            background:
            linear-gradient(rgba(8,15,35,0.92),
            rgba(8,15,35,0.95)),
            url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop');

            background-size:cover;
            background-position:center;
            background-attachment:fixed;

            min-height:100vh;

            padding:40px;

            color:white;
        }

        .box{

            max-width:1000px;

            margin:auto;

            padding:40px;

            box-shadow:0 10px 30px rgba(0,0,0,0.35);
        }

        h2{
	font-family:'Cormorant Garamond',serif;
            color:#7dd3fc;

            margin-bottom:20px;

            font-size:32px;
        }

        .success{

            background:rgba(34,197,94,0.2);

            border:1px solid rgba(34,197,94,0.3);

            padding:15px;

            border-radius:12px;

            margin-bottom:15px;
        }

        .error{

            background:rgba(239,68,68,0.2);

            border:1px solid rgba(239,68,68,0.3);

            padding:15px;

            border-radius:12px;

            margin-bottom:15px;
        }

        /* FF ENROLL BANNER */

        .ff-enroll{

            background:linear-gradient(135deg,rgba(234,179,8,0.2),rgba(251,146,60,0.2));

            border:1px solid rgba(234,179,8,0.4);

            padding:18px 22px;

            border-radius:14px;

            margin-bottom:15px;

            display:flex;
            align-items:center;
            gap:14px;

            font-size:15px;
        }

        .ff-enroll i{

            font-size:28px;
            color:#fbbf24;
        }

        .ff-enroll strong{

            color:#fde68a;
            display:block;
            font-size:17px;
            margin-bottom:4px;
        }

        .payment-btn{

            display:inline-block;

            margin-top:25px;

            padding:14px 30px;

            background:linear-gradient(135deg,#2563eb,#38bdf8);

            color:white;

            text-decoration:none;

            border-radius:14px;

            font-weight:600;

            transition:0.3s;
        }

        .payment-btn:hover{

            transform:translateY(-3px);

            box-shadow:0 10px 20px rgba(37,99,235,0.4);
        }

    </style>

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

</head>

<body>

<div class="box">

<?php

if(isset($_POST['Submit']))
{

    $pnr = rand(1000000,9999999);

    $date_of_res = date("Y-m-d");

    $flight_no = isset($_SESSION['flight_no'])
                 ? $_SESSION['flight_no']
                 : "";

    $journey_date = isset($_SESSION['journey_date'])
                     ? $_SESSION['journey_date']
                     : date("Y-m-d");

    $class = isset($_SESSION['class'])
             ? strtolower($_SESSION['class'])
             : "economy";

    $no_of_pass = isset($_SESSION['no_of_pass'])
                  ? $_SESSION['no_of_pass']
                  : 1;

    $booking_status = "PENDING";

    $lounge_access    = $_POST['lounge_access'];
    $priority_checkin = $_POST['priority_checkin'];
    $insurance        = $_POST['insurance'];

    $total_no_of_meals = 0;

    $_SESSION['pnr']              = $pnr;
    $_SESSION['lounge_access']    = $lounge_access;
    $_SESSION['priority_checkin'] = $priority_checkin;
    $_SESSION['insurance']        = $insurance;

    $payment_id = NULL;

    /* ── session key fixed to match login.php ── */
    if(isset($_SESSION['userid']))
    {
        $customer_id = $_SESSION['userid'];
    }
    else
    {
        die("<div class='error'>Customer not logged in</div>");
    }

    require_once('Database Connection file/mysqli_connect.php');

    /* ── verify customer exists ── */

    $query = "SELECT customer_id FROM customer WHERE customer_id=?";

    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "s", $customer_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if($count == 0)
    {
        die("<div class='error'>Customer ID does not exist in database</div>");
    }

    /* ── get ticket price ── */

    if($class == "economy")
    {
        $query = "SELECT price_economy FROM flight_details WHERE flight_no=?";
    }
    else
    {
        $query = "SELECT price_business FROM flight_details WHERE flight_no=?";
    }

    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, "s", $flight_no);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $ticket_price);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if(empty($ticket_price)) { $ticket_price = 5000; }

    $ff_mileage = $ticket_price / 10;

    /* ── insert ticket ── */

    $query = "INSERT INTO ticket_details
    (
        pnr, date_of_reservation, flight_no, journey_date,
        class, booking_status, no_of_passengers,
        lounge_access, priority_checkin, insurance,
        payment_id, customer_id
    )
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($dbc, $query);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssisssss",
        $pnr, $date_of_res, $flight_no, $journey_date,
        $class, $booking_status, $no_of_pass,
        $lounge_access, $priority_checkin, $insurance,
        $payment_id, $customer_id
    );

    $execute = mysqli_stmt_execute($stmt);

    if($execute)
    {
        echo "<div class='success'>
                <h2>Ticket Successfully Booked ✈</h2>
              </div>";
    }
    else
    {
        die("<div class='error'>Ticket Insert Error: " . mysqli_error($dbc) . "</div>");
    }

    mysqli_stmt_close($stmt);

    /* ════════════════════════════════════════════
       AUTO-ENROLL INTO FREQUENT FLYER
       After every successful booking, count the
       customer's total bookings. If >= 3 and not
       already enrolled, enrol them automatically.
    ════════════════════════════════════════════ */

    /* Step 1: count total bookings for this customer */

    $cq = "SELECT COUNT(*) FROM ticket_details WHERE customer_id=?";
    $cst = mysqli_prepare($dbc, $cq);
    mysqli_stmt_bind_param($cst, "s", $customer_id);
    mysqli_stmt_execute($cst);
    mysqli_stmt_bind_result($cst, $total_bookings);
    mysqli_stmt_fetch($cst);
    mysqli_stmt_close($cst);

    /* Step 2: check if already enrolled */

    $eq = "SELECT COUNT(*) FROM frequent_flier_details WHERE customer_id=?";
    $est = mysqli_prepare($dbc, $eq);
    mysqli_stmt_bind_param($est, "s", $customer_id);
    mysqli_stmt_execute($est);
    mysqli_stmt_bind_result($est, $already_enrolled);
    mysqli_stmt_fetch($est);
    mysqli_stmt_close($est);

    /* Step 3: enrol if 3+ bookings and not yet enrolled */

    if($total_bookings >= 3 && $already_enrolled == 0)
    {
        /* generate a unique FF number e.g. FF-SITA-001 */
        $ff_no = 'FF-' . strtoupper($customer_id) . '-' . rand(100,999);

        $iq = "INSERT INTO frequent_flier_details
               (frequent_flier_no, customer_id, mileage)
               VALUES (?, ?, 0)";

        $ist = mysqli_prepare($dbc, $iq);
        mysqli_stmt_bind_param($ist, "ss", $ff_no, $customer_id);
        $enrolled = mysqli_stmt_execute($ist);
        mysqli_stmt_close($ist);

        if($enrolled)
        {
            echo "<div class='ff-enroll'>
                    <i class='fa-solid fa-crown'></i>
                    <div>
                        <strong>🎉 Congratulations! You're now a Frequent Flyer!</strong>
                        You have been automatically enrolled in the Skyvelle
                        Frequent Flyer programme.<br>
                        Your FF Number: <strong style='color:#fbbf24;'>"
                        . htmlspecialchars($ff_no) .
                        "</strong>
                    </div>
                  </div>";
        }
    }

    /* ── passengers loop ── */

    for($i = 1; $i <= $no_of_pass; $i++)
    {
        $ff_id = $_POST['pass_ff_id'][$i-1];
        $cnt   = 0;

        if(!empty($ff_id))
        {
            $query = "SELECT count(*)
                      FROM customer c, frequent_flier_details f
                      WHERE c.name=?
                      AND f.frequent_flier_no=?
                      AND c.customer_id=f.customer_id";

            $stmt = mysqli_prepare($dbc, $query);
            mysqli_stmt_bind_param($stmt, "ss", $_POST['pass_name'][$i-1], $ff_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cnt);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if($cnt == 1)
            {
                $query = "UPDATE frequent_flier_details
                          SET mileage = mileage + ?
                          WHERE frequent_flier_no=?";

                $stmt = mysqli_prepare($dbc, $query);
                mysqli_stmt_bind_param($stmt, "is", $ff_mileage, $ff_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            else
            {
                $ff_id = NULL;
            }
        }
        else
        {
            $ff_id = NULL;
        }

        if($_POST['pass_meal'][$i-1] == 'yes')
        {
            $total_no_of_meals++;
        }

        $query = "INSERT INTO passengers
        (pnr, name, age, gender, meal_choice, frequent_flier_no)
        VALUES (?,?,?,?,?,?)";

        $stmt = mysqli_prepare($dbc, $query);

        mysqli_stmt_bind_param(
            $stmt,
            "isisss",
            $pnr,
            $_POST['pass_name'][$i-1],
            $_POST['pass_age'][$i-1],
            $_POST['pass_gender'][$i-1],
            $_POST['pass_meal'][$i-1],
            $ff_id
        );

        $execute = mysqli_stmt_execute($stmt);

        if($execute)
        {
            echo "<div class='success'>Passenger " . $i . " Added Successfully</div>";
        }
        else
        {
            echo "<div class='error'>Passenger Insert Error: " . mysqli_error($dbc) . "</div>";
        }

        mysqli_stmt_close($stmt);
    }

    $_SESSION['total_no_of_meals'] = $total_no_of_meals;

    mysqli_close($dbc);

    echo "<a href='select_seats.php' class='payment-btn'>
            <i class='fa-solid fa-chair'></i> Select Your Seats
          </a>";
}
else
{
    echo "<div class='error'>Submit request not received</div>";
}

?>

</div>

</body>
</html>