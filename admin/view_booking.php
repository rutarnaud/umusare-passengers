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


// ========================================
// Prepare Customer Phone for WhatsApp
// ========================================

$phone = preg_replace('/[^0-9+]/', '', $booking['phone']);


// Rwanda local number: 078... -> 25078...
if(str_starts_with($phone, '0')){

    $whatsappPhone = '250' . substr($phone, 1);

}elseif(str_starts_with($phone, '+250')){

    $whatsappPhone = substr($phone, 1);

}else{

    $whatsappPhone = $phone;

}


// ========================================
// WhatsApp Message
// ========================================

$whatsappMessage =
    "Hello " . $booking['name'] .
    ", this is Umusare Passengers regarding your booking." .
    "\n\n" .
    "Booking ID: #" . $booking['id'] .
    "\n" .
    "Vehicle: " . $booking['vehicle'] .
    "\n" .
    "Pickup Date: " . $booking['pickup_date'] .
    "\n" .
    "Return Date: " . $booking['return_date'] .
    "\n" .
    "Service: " . $booking['service'] .
    "\n" .
    "Status: " . $booking['status'] .
    "\n\n" .
    "Thank you for choosing Umusare Passengers.";


$whatsappURL =
    "https://wa.me/" .
    $whatsappPhone .
    "?text=" .
    urlencode($whatsappMessage);

?>

<!DOCTYPE html>

<html>

<head>

<title>
Booking Details | Umusare Passengers
</title>


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


.actions{

    margin-top:25px;

    padding:20px 0;

}


.actions button{

    padding:12px 20px;

    border:none;

    border-radius:6px;

    cursor:pointer;

    font-weight:bold;

    margin-right:10px;

}


.confirm-btn{

    background:#28a745;

    color:white;

}


.cancel-btn{

    background:#dc3545;

    color:white;

}


.contact-actions{

    margin-top:20px;

    padding:20px;

    background:#f8f9fa;

    border-radius:10px;

}


.contact-btn{

    display:inline-block;

    padding:12px 20px;

    color:white;

    text-decoration:none;

    border-radius:6px;

    font-weight:bold;

    margin-right:10px;

    margin-bottom:10px;

}


.call-btn{

    background:#0B1F3A;

}


.whatsapp-btn{

    background:#25D366;

}


.contact-btn:hover{

    opacity:.85;

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


<!-- =====================================
     Contact Customer
===================================== -->

<div class="contact-actions">

<strong>
Contact Customer
</strong>

<br><br>


<a
href="tel:<?php echo htmlspecialchars($booking['phone']); ?>"
class="contact-btn call-btn"
>
📞 Call Customer
</a>


<a
href="<?php echo htmlspecialchars($whatsappURL); ?>"
target="_blank"
rel="noopener noreferrer"
class="contact-btn whatsapp-btn"
>
💬 WhatsApp Customer
</a>


</div>


<!-- =====================================
     Booking Actions
===================================== -->

<div class="actions">


<?php if($booking['status'] != "Confirmed"){ ?>


<form
action="update_status.php"
method="POST"
style="display:inline;"
>


<input
type="hidden"
name="id"
value="<?php echo $booking['id']; ?>"
>


<input
type="hidden"
name="status"
value="Confirmed"
>


<button
type="submit"
class="confirm-btn"
onclick="return confirm('Confirm this booking?');"
>

🟢 Confirm Booking

</button>


</form>


<?php } ?>


<?php if($booking['status'] != "Cancelled"){ ?>


<form
action="update_status.php"
method="POST"
style="display:inline;"
>


<input
type="hidden"
name="id"
value="<?php echo $booking['id']; ?>"
>


<input
type="hidden"
name="status"
value="Cancelled"
>


<button
type="submit"
class="cancel-btn"
onclick="return confirm('Cancel this booking?');"
>

🔴 Cancel Booking

</button>


</form>


<?php } ?>


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
class="back-btn"
>

⬅ Back to Bookings

</a>


</div>


</body>

</html>