<?php

include "config.php";

header('Content-Type: application/json');


// ========================================
// Get Data
// ========================================

$vehicle = trim($_GET['vehicle'] ?? '');

$pickup_date = trim(
    $_GET['pickup_date'] ?? ''
);

$return_date = trim(
    $_GET['return_date'] ?? ''
);


// ========================================
// Check Required Data
// ========================================

if(
    empty($vehicle) ||
    empty($pickup_date) ||
    empty($return_date)
){

    echo json_encode([
        "available" => false,
        "message" => "Please select vehicle and dates."
    ]);

    exit();

}


// ========================================
// Validate Dates
// ========================================

$pickup = DateTime::createFromFormat(
    'Y-m-d',
    $pickup_date
);

$return = DateTime::createFromFormat(
    'Y-m-d',
    $return_date
);


if(
    !$pickup ||
    !$return ||
    $pickup->format('Y-m-d') !== $pickup_date ||
    $return->format('Y-m-d') !== $return_date
){

    echo json_encode([
        "available" => false,
        "message" => "Invalid date format."
    ]);

    exit();

}


// ========================================
// Return Date Cannot Be Before Pickup
// ========================================

if($return < $pickup){

    echo json_encode([
        "available" => false,
        "message" => "Return date cannot be before pickup date."
    ]);

    exit();

}


// ========================================
// Check Vehicle Exists
// ========================================

$stmt = $conn->prepare(
    "SELECT id
     FROM vehicles
     WHERE name = ?
     LIMIT 1"
);


$stmt->bind_param(
    "s",
    $vehicle
);


$stmt->execute();

$stmt->store_result();


if($stmt->num_rows === 0){

    $stmt->close();

    echo json_encode([
        "available" => false,
        "message" => "Selected vehicle does not exist."
    ]);

    exit();

}


$stmt->close();


// ========================================
// Check Overlapping Bookings
// ========================================

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


// ========================================
// Result
// ========================================

if($stmt->num_rows > 0){

    echo json_encode([
        "available" => false,
        "message" =>
            "This vehicle is not available for the selected dates."
    ]);

}else{

    echo json_encode([
        "available" => true,
        "message" =>
            "This vehicle is available for the selected dates."
    ]);

}


$stmt->close();

$conn->close();

?>