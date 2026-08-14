<?php

include "config.php";


// ========================================
// Get Booking ID
// ========================================

$id = intval($_GET['id'] ?? 0);


if($id <= 0){

    die("Invalid booking ID.");

}


// ========================================
// Get Booking
// ========================================

$stmt = $conn->prepare(
    "SELECT
        id,
        name,
        phone,
        email,
        vehicle,
        pickup_date,
        return_date,
        service,
        message,
        status
     FROM bookings
     WHERE id = ?
     LIMIT 1"
);


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$result = $stmt->get_result();


$booking = $result->fetch_assoc();


$stmt->close();


if(!$booking){

    die("Booking not found.");

}


$conn->close();


// ========================================
// Status Display
// ========================================

$status = $booking['status'];


if($status === "Confirmed"){

    $statusClass = "confirmed";
    $statusIcon = "🟢";

}elseif($status === "Cancelled"){

    $statusClass = "cancelled";
    $statusIcon = "🔴";

}else{

    $statusClass = "pending";
    $statusIcon = "🟡";

}

?>


<!DOCTYPE html>

<html lang="en">


<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">


<title>
Booking #<?php echo $booking['id']; ?>
| Umusare Passengers
</title>


<style>

*{
    box-sizing:border-box;
}


body{

    margin:0;

    font-family:Arial, sans-serif;

    background:#f5f5f5;

    color:#333;

}


.container{

    max-width:750px;

    margin:60px auto;

    padding:20px;

}


.booking-card{

    background:white;

    padding:35px;

    border-radius:15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,.08);

}


.header{

    text-align:center;

    margin-bottom:30px;

}


.header h1{

    color:#0B1F3A;

    margin-bottom:10px;

}


.header p{

    color:#666;

}


.booking-id{

    display:inline-block;

    background:#0B1F3A;

    color:white;

    padding:8px 15px;

    border-radius:20px;

    font-weight:bold;

}


.status{

    text-align:center;

    padding:18px;

    border-radius:10px;

    margin-bottom:30px;

    font-size:20px;

    font-weight:bold;

}


.status.pending{

    background:#fff3cd;

    color:#856404;

}


.status.confirmed{

    background:#d4edda;

    color:#155724;

}


.status.cancelled{

    background:#f8d7da;

    color:#721c24;

}


.details{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:15px;

}


.detail{

    padding:15px;

    background:#f8f9fa;

    border-radius:8px;

}


.detail strong{

    display:block;

    color:#0B1F3A;

    margin-bottom:6px;

}


.message{

    margin-top:20px;

    padding:18px;

    background:#f8f9fa;

    border-radius:8px;

}


.message strong{

    color:#0B1F3A;

}


.buttons{

    text-align:center;

    margin-top:30px;

}


.btn{

    display:inline-block;

    padding:12px 20px;

    margin:5px;

    border-radius:7px;

    text-decoration:none;

    font-weight:bold;

}


.home-btn{

    background:#0B1F3A;

    color:white;

}


.new-btn{

    background:#D4AF37;

    color:#111;

}


@media(max-width:600px){

    .container{

        margin:20px auto;

    }


    .booking-card{

        padding:20px;

    }


    .details{

        grid-template-columns:1fr;

    }

}

</style>


</head>


<body>


<div class="container">


<div class="booking-card">


<div class="header">


<h1>
Booking Submitted Successfully! 🎉
</h1>


<p>
Thank you
<strong>
<?php echo htmlspecialchars($booking['name']); ?>
</strong>.
</p>


<span class="booking-id">

Booking #<?php echo htmlspecialchars($booking['id']); ?>

</span>


</div>


<!-- =====================================
     STATUS
===================================== -->


<div class="status <?php echo $statusClass; ?>">

<?php echo $statusIcon; ?>

<?php echo htmlspecialchars($status); ?>


</div>


<!-- =====================================
     BOOKING DETAILS
===================================== -->


<div class="details">


<div class="detail">

<strong>
Customer
</strong>

<?php echo htmlspecialchars($booking['name']); ?>

</div>


<div class="detail">

<strong>
Phone
</strong>

<?php echo htmlspecialchars($booking['phone']); ?>

</div>


<div class="detail">

<strong>
Vehicle
</strong>

<?php echo htmlspecialchars($booking['vehicle']); ?>

</div>


<div class="detail">

<strong>
Service
</strong>

<?php echo htmlspecialchars($booking['service']); ?>

</div>


<div class="detail">

<strong>
Pick-up Date
</strong>

<?php echo htmlspecialchars($booking['pickup_date']); ?>

</div>


<div class="detail">

<strong>
Return Date
</strong>

<?php echo htmlspecialchars($booking['return_date']); ?>

</div>


<?php if(!empty($booking['email'])){ ?>

<div class="detail">

<strong>
Email
</strong>

<?php echo htmlspecialchars($booking['email']); ?>

</div>

<?php } ?>


</div>


<!-- =====================================
     MESSAGE
===================================== -->


<div class="message">

<strong>
Your Message
</strong>


<br><br>


<?php

if(!empty($booking['message'])){

    echo nl2br(
        htmlspecialchars($booking['message'])
    );

}else{

    echo "No additional message.";

}

?>

</div>


<!-- =====================================
     STATUS MESSAGE
===================================== -->


<div class="message">


<?php if($status === "Pending"){ ?>

<strong>
⏳ Booking is waiting for confirmation.
</strong>

<br><br>

Our team will review your booking
and contact you soon.

<?php } ?>


<?php if($status === "Confirmed"){ ?>

<strong>
🎉 Your booking has been confirmed!
</strong>

<br><br>

Your vehicle is reserved for the selected
dates.

<?php } ?>


<?php if($status === "Cancelled"){ ?>

<strong>
❌ This booking has been cancelled.
</strong>

<br><br>

Please contact Umusare Passengers if
you need further assistance.

<?php } ?>


</div>


<!-- =====================================
     BUTTONS
===================================== -->


<div class="buttons">


<a
href="index.php"
class="btn home-btn">

⬅ Back Home

</a>


<a
href="booking.php"
class="btn new-btn">

🚗 Make Another Booking

</a>


</div>


</div>


</div>


</body>


</html>