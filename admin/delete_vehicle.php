<?php

session_start();


// ========================================
// Check Admin Login
// ========================================

if(!isset($_SESSION['admin'])){

    header("Location: login.php");
    exit();

}

include "../config.php";


// ========================================
// Get Vehicle ID Safely
// ========================================

$id = intval($_GET['id'] ?? 0);


if($id <= 0){

    die("Invalid vehicle ID.");

}


// ========================================
// Get Vehicle
// ========================================

$stmt = $conn->prepare(
    "SELECT image FROM vehicles WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$result = $stmt->get_result();

$vehicle = $result->fetch_assoc();

$stmt->close();


// ========================================
// Check Vehicle Exists
// ========================================

if(!$vehicle){

    die("Vehicle not found.");

}


$image = $vehicle['image'];


// ========================================
// Delete Vehicle From Database
// ========================================

$stmt = $conn->prepare(
    "DELETE FROM vehicles WHERE id = ?"
);


$stmt->bind_param(
    "i",
    $id
);


if($stmt->execute()){


    $stmt->close();


    // ====================================
    // Database deletion succeeded.
    // Now delete image.
    // ====================================

    if(!empty($image)){


        $image_path =
            "../assets/images/"
            . basename($image);


        if(file_exists($image_path)){

            unlink($image_path);

        }

    }


    // ====================================
    // Success Message
    // ====================================

    $_SESSION['success'] =
        "Vehicle deleted successfully!";


    header("Location: vehicles.php");

    exit();


}else{


    echo "Error deleting vehicle: "
         . htmlspecialchars($stmt->error);


    $stmt->close();

}

?>