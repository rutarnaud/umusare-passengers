<?php

include "config.php";


$id = intval($_GET['id'] ?? 0);


$stmt = $conn->prepare(
    "SELECT *
     FROM bookings
     WHERE id = ?"
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

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Booking Confirmed | Umusare Passengers</title>

<style>

body{

    margin:0;

    font-family:Arial, sans-serif;

    background:#f5f5f5;

    padding:40px 20px;

}


.container{

    max-width:650px;

    margin:auto;

    background:white;

    padding:35px;

    border-radius:15px;

    box-shadow:0 5px 25px rgba(0,0,0,.1);

}


.success{

    text-align:center;

    font-size:50px;

}


h1{

    text-align:center;

    color:#0B1F3A;

}


.subtitle{

    text-align:center;

    color:#666;

}


.booking-id{

    background:#0B1F3A;

    color:white;

    padding:15px;

    border-radius:8px;

    text-align:center;

    margin:25px 0;

}


.booking-id strong{

    font-size:22px;

}


.details{

    margin-top:20px;

}


.detail{

    display:flex;

    justify-content:space-between;

    padding:14px 0;

    border-bottom:1px solid #eee;

}


.label{

    font-weight:bold;

    color:#0B1F3A;

}


.status{

    color:#856404;

    font-weight:bold;

}


.message{

    background:#f5f5f5;

    padding:15px;

    border-radius:8px;

    margin-top:15px;

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


.home{

    background:#0B1F3A;

    color:white;

}


.whatsapp{

    background:#25D366;

    color:white;

}


</style>

</head>


<body>


<div class="container">


<div class="success">

✅

</div>


<h1>
Booking Submitted Successfully!
</h1>


<p class="subtitle">

Thank you
<strong>
<?php echo htmlspecialchars($booking['name']); ?>
</strong>.

We have received your booking request.

</p>


<div class="booking-id">

Your Booking ID

<br>

<strong>

#<?php echo htmlspecialchars($booking['id']); ?>

</strong>

</div>


<div class="details">


<div class="detail">

<span class="label">
Vehicle
</span>

<span>
<?php echo htmlspecialchars($booking['vehicle']); ?>
</span>

</div>


<div class="detail">

<span class="label">
Pickup Date
</span>

<span>
<?php echo htmlspecialchars($booking['pickup_date']); ?>
</span>

</div>


<div class="detail">

<span class="label">
Return Date
</span>

<span>
<?php echo htmlspecialchars($booking['return_date']); ?>
</span>

</div>


<div class="detail">

<span class="label">
Service
</span>

<span>
<?php echo htmlspecialchars($booking['service']); ?>
</span>

</div>


<div class="detail">

<span class="label">
Status
</span>

<span class="status">
🟡 Pending
</span>

</div>


</div>


<?php if(!empty($booking['message'])){ ?>


<div class="message">

<strong>
Your Message
</strong>

<br><br>

<?php

echo nl2br(
    htmlspecialchars($booking['message'])
);

?>

</div>


<?php } ?>


<div class="buttons">


<a
href="index.php"
class="btn home">

⬅ Back Home

</a>


</div>


</div>


</body>

</html>