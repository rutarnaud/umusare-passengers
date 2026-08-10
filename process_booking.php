<?php

// Database connection

$servername = "localhost";
$username = "root";
$password = "";
$database = "umusare_db";


// Create connection

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);


// Check connection

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}


// Get form data

$name = $_POST['name'];

$phone = $_POST['phone'];

$email = $_POST['email'];

$vehicle = $_POST['vehicle'];

$pickup_date = $_POST['pickup_date'];

$return_date = $_POST['return_date'];

$service = $_POST['service'];

$message = $_POST['message'];
// Validate booking dates

$today = date('Y-m-d');

if($pickup_date < $today){

    die("Error: Pickup date cannot be in the past.");

}


if($return_date < $pickup_date){

    die("Error: Return date cannot be before pickup date.");

}

// Check vehicle availability

$check = $conn->prepare(
    "SELECT id FROM bookings
     WHERE vehicle = ?
     AND status IN ('Pending', 'Confirmed')
     AND pickup_date < ?
     AND return_date > ?"
);

$check->bind_param(
    "sss",
    $vehicle,
    $return_date,
    $pickup_date
);

$check->execute();

$check->store_result();


if($check->num_rows > 0){

    $check->close();

    die("
        <h2>Vehicle Not Available</h2>

        <p>
        Sorry, this vehicle is already booked
        for the selected dates.
        </p>

        <a href='booking.php'>
        Choose Different Dates
        </a>
    ");

}


$check->close();

// Insert data

$stmt = $conn->prepare(
    "INSERT INTO bookings
    (name, phone, email, vehicle, pickup_date, return_date, service, message)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssssssss",
    $name,
    $phone,
    $email,
    $vehicle,
    $pickup_date,
    $return_date,
    $service,
    $message
);


if($stmt->execute()){

    echo "

    <h2>Booking Submitted Successfully!</h2>

    <p>Thank you " . htmlspecialchars($name) . ". We will contact you soon.</p>

    <a href='index.php'>Back Home</a>

    ";

}else{

    echo "Error: " . htmlspecialchars($stmt->error);

}


$stmt->close();



$conn->close();


?>