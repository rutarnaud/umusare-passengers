<?php

include "config.php";


// ========================================
// Get Form Data
// ========================================

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$vehicle = trim($_POST['vehicle'] ?? '');
$pickup_date = trim($_POST['pickup_date'] ?? '');
$return_date = trim($_POST['return_date'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');


// ========================================
// Required Fields
// ========================================

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


// ========================================
// Validate Email
// ========================================

if(
    !empty($email) &&
    !filter_var($email, FILTER_VALIDATE_EMAIL)
){

    die("Please enter a valid email address.");

}


// ========================================
// Validate Service
// ========================================

$allowed_services = [
    "Self Drive",
    "With Driver"
];


if(!in_array($service, $allowed_services, true)){

    die("Invalid service type.");

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

    die("Invalid booking dates.");

}


if($return < $pickup){

    die("Return date cannot be before pickup date.");

}


// ========================================
// Start Transaction
// ========================================

$conn->begin_transaction();


try{


    // ====================================
    // Lock Vehicle Row
    // ====================================

    $stmt = $conn->prepare(
        "SELECT id, status
         FROM vehicles
         WHERE name = ?
         LIMIT 1
         FOR UPDATE"
    );


    if(!$stmt){

        throw new Exception(
            "Unable to prepare vehicle query."
        );

    }


    $stmt->bind_param(
        "s",
        $vehicle
    );


    $stmt->execute();


    $result = $stmt->get_result();


    $vehicleData = $result->fetch_assoc();


    $stmt->close();


    // ====================================
    // Vehicle Does Not Exist
    // ====================================

    if(!$vehicleData){

        throw new Exception(
            "Selected vehicle does not exist."
        );

    }


    // ====================================
    // Check Maintenance Status
    // ====================================

    if($vehicleData['status'] === 'Maintenance'){

        throw new Exception(
            "This vehicle is currently under maintenance."
        );

    }


    // ====================================
    // Check Overlapping Bookings
    // ====================================

    $stmt = $conn->prepare(
        "SELECT id
         FROM bookings
         WHERE vehicle = ?
         AND status IN ('Pending', 'Confirmed')
         AND pickup_date < ?
         AND return_date > ?
         LIMIT 1"
    );


    if(!$stmt){

        throw new Exception(
            "Unable to prepare availability query."
        );

    }


    $stmt->bind_param(
        "sss",
        $vehicle,
        $return_date,
        $pickup_date
    );


    $stmt->execute();


    $stmt->store_result();


    // ====================================
    // Vehicle Already Booked
    // ====================================

    if($stmt->num_rows > 0){

        $stmt->close();

        throw new Exception(
            "This vehicle is not available for the selected dates."
        );

    }


    $stmt->close();


    // ====================================
    // Insert Booking
    // ====================================

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


    if(!$stmt){

        throw new Exception(
            "Unable to prepare booking query."
        );

    }


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


    if(!$stmt->execute()){

        throw new Exception(
            "Unable to save booking."
        );

    }


    $booking_id = $stmt->insert_id;


    $stmt->close();


    // ====================================
    // Commit Transaction
    // ====================================

    $conn->commit();


    $conn->close();


    // ====================================
    // Success
    // ====================================

    header(
        "Location: booking_success.php?id="
        . intval($booking_id)
    );

    exit();


}
catch(Exception $e){


    // ====================================
    // Rollback
    // ====================================

    $conn->rollback();


    $conn->close();


    // ====================================
    // Show User-Friendly Error
    // ====================================

    if(
        $e->getMessage()
        === "This vehicle is not available for the selected dates."
    ){

        header(
            "Location: booking.php?error=unavailable"
        );

        exit();

    }


    die(
        htmlspecialchars(
            $e->getMessage()
        )
    );

}

?>