<?php

include "config.php";


// Get form data

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$vehicle = trim($_POST['vehicle'] ?? '');
$pickup_date = $_POST['pickup_date'] ?? '';
$return_date = $_POST['return_date'] ?? '';
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');


// Basic validation

if(
    empty($name) ||
    empty($phone) ||
    empty($vehicle) ||
    empty($pickup_date) ||
    empty($return_date) ||
    empty($service)
){

    die("Please fill in all required fields.");

}


// Check date order

if($return_date < $pickup_date){

    die("Return date cannot be before pickup date.");

}


// Check vehicle availability again

$stmt = $conn->prepare(
    "SELECT id
     FROM bookings
     WHERE vehicle = ?
     AND status != 'Cancelled'
     AND pickup_date <= ?
     AND return_date >= ?"
);

$stmt->bind_param(
    "sss",
    $vehicle,
    $return_date,
    $pickup_date
);

$stmt->execute();

$result = $stmt->get_result();


// Vehicle already booked

if($result->num_rows > 0){

    $stmt->close();

    header("Location: booking.php?error=unavailable");

    exit();

}

$stmt->close();


// Insert booking

$stmt = $conn->prepare(
    "INSERT INTO bookings
    (
        name,
        phone,
        email,
        vehicle,
        pickup_date,
        return_date,
        service,
        message,
        status
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')"
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

    $booking_id = $stmt->insert_id;

    $stmt->close();

    header(
        "Location: booking_success.php?id="
        . $booking_id
    );

    exit();

}else{

    echo "Error: " . $stmt->error;

}


$stmt->close();

$conn->close();

?>