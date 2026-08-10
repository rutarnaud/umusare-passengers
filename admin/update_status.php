<?php

session_start();


// Check admin login

if(!isset($_SESSION['admin'])){

    header("Location: login.php");

    exit();

}


include "../config.php";


// Check submitted data

if(isset($_POST['id']) && isset($_POST['status'])){


    $id = intval($_POST['id']);

    $status = $_POST['status'];


    // Only allow valid statuses

    $allowedStatuses = [
        "Pending",
        "Confirmed",
        "Cancelled"
    ];


    if(!in_array($status, $allowedStatuses)){

        die("Invalid booking status.");

    }


    // Prepared statement

    $stmt = $conn->prepare(
        "UPDATE bookings
         SET status = ?
         WHERE id = ?"
    );


    $stmt->bind_param(
        "si",
        $status,
        $id
    );


    if($stmt->execute()){

        $stmt->close();

        header("Location: index.php");

        exit();

    }else{

        echo "Error updating booking: "
             . $stmt->error;

    }


}else{


    echo "Missing booking data.";


}


$conn->close();

?>