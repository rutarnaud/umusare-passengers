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
// Check Booking ID
// ========================================

if(!isset($_POST['id'])){

    die("Missing booking ID.");

}


$id = intval($_POST['id']);


if($id <= 0){

    die("Invalid booking ID.");

}


// ========================================
// Delete Booking Safely
// ========================================

$stmt = $conn->prepare(
    "DELETE FROM bookings WHERE id = ?"
);


$stmt->bind_param(
    "i",
    $id
);


if($stmt->execute()){

    if($stmt->affected_rows > 0){

        $_SESSION['success'] =
            "Booking deleted successfully!";

    }else{

        $_SESSION['success'] =
            "Booking was not found.";

    }


    $stmt->close();

    header("Location: index.php");

    exit();


}else{

    echo "Error deleting booking: "
         . $stmt->error;

}


$stmt->close();

$conn->close();

?>