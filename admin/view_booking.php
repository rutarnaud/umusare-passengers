<?php

session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");

    exit();

}

include "../config.php";


$id = intval($_GET['id'] ?? 0);


$stmt = $conn->prepare(
    "SELECT * FROM bookings WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$booking = $result->fetch_assoc();

$stmt->close();


if(!$booking){

    die("Booking not found.");

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Booking Details | Umusare Passengers</title>

<style>

body{

    font-family:Arial;

    background:#f5f5f5;

    padding:40px;

}


.container{

    max-width:700px;

    margin:auto;

    background:white;

    padding:30px;

    border-radius:12px;

    box-shadow:0 5px 20px rgba(0,0,0,.1);

}


h1{

    color:#0B1F3A;

    margin-bottom:25px;

}


.detail{

    padding:15px 0;

    border-bottom:1px solid #eee;

}


.detail strong{

    display:inline-block;

    width:150px;

    color:#0B1F3A;

}


.message{

    background:#f5f5f5;

    padding:15px;

    margin-top:10px;

    border-radius:8px;

}


.back-btn{

    display:inline-block;

    margin-top:25px;

    padding:10px 18px;

    background:#0B1F3A;

    color:white;

    text-decoration:none;

    border-radius:6px;

}


</style>

</head>


<body>


<div class="container">


<h1>
Booking Details
</h1>


<div class="detail">

<strong>Booking ID:</strong>

<?php echo htmlspecialchars($booking['id']); ?>

</div>


<div class="detail">

<strong>Customer Name:</strong>

<?php echo htmlspecialchars($booking['name']); ?>

</div>


<div class="detail">

<strong>Phone:</strong>

<?php echo htmlspecialchars($booking['phone']); ?>

</div>


<div class="detail">

<strong>Email:</strong>

<?php echo htmlspecialchars($booking['email']); ?>

</div>


<div class="detail">

<strong>Vehicle:</strong>

<?php echo htmlspecialchars($booking['vehicle']); ?>

</div>


<div class="detail">

<strong>Pickup Date:</strong>

<?php echo htmlspecialchars($booking['pickup_date']); ?>

</div>


<div class="detail">

<strong>Return Date:</strong>

<?php echo htmlspecialchars($booking['return_date']); ?>

</div>


<div class="detail">

<strong>Service:</strong>

<?php echo htmlspecialchars($booking['service']); ?>

</div>


<div class="detail">

<strong>Status:</strong>

<?php

if($booking['status'] == "Confirmed"){

    echo "🟢 Confirmed";

}elseif($booking['status'] == "Cancelled"){

    echo "🔴 Cancelled";

}else{

    echo "🟡 Pending";

}

?>

</div>


<div class="detail">

<strong>Message:</strong>

<div class="message">

<?php

if(!empty($booking['message'])){

    echo nl2br(
        htmlspecialchars($booking['message'])
    );

}else{

    echo "No message provided.";

}

?>

</div>

</div>


<a
href="index.php"
class="back-btn">
⬅ Back to Bookings
</a>


</div>


</body>

</html>