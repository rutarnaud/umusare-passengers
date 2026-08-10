<?php

include "config.php";

header('Content-Type: application/json');


// Get data

$vehicle = $_GET['vehicle'] ?? '';
$pickup_date = $_GET['pickup_date'] ?? '';
$return_date = $_GET['return_date'] ?? '';


// Check required data

if(empty($vehicle) || empty($pickup_date) || empty($return_date)){

    echo json_encode([
        "available" => false,
        "message" => "Please select vehicle and dates."
    ]);

    exit();

}


// Check overlapping bookings

$stmt = $conn->prepare(
    "SELECT id
     FROM bookings
     WHERE vehicle = ?
     AND status IN ('Pending', 'Confirmed')
     AND pickup_date < ?
     AND return_date > ?
     LIMIT 1"
);


$stmt->bind_param(
    "sss",
    $vehicle,
    $return_date,
    $pickup_date
);


$stmt->execute();

$stmt->store_result();


if($stmt->num_rows > 0){

    echo json_encode([
        "available" => false,
        "message" => "This vehicle is not available for the selected dates."
    ]);

}else{

    echo json_encode([
        "available" => true,
        "message" => "This vehicle is available for the selected dates."
    ]);

}


$stmt->close();

$conn->close();

?>