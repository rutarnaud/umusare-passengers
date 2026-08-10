<?php

session_start();


if(!isset($_SESSION['admin'])){

    header("Location: login.php");

    exit();

}


include "../config.php";


$id = intval($_GET['id']);


// First get vehicle image

$stmt = $conn->prepare("SELECT image FROM vehicles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

$stmt->close();


$image = $vehicle['image'];


// Delete image from folder

if(!empty($image)){

    $image_path = "../assets/images/".$image;

    if(file_exists($image_path)){

        unlink($image_path);

    }

}


// Delete vehicle from database

$stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
$stmt->bind_param("i", $id);

if($stmt->execute()){

    $_SESSION['success'] = "Vehicle deleted successfully!";

    header("Location: vehicles.php");
    exit();

}
else{


    echo "Error deleting vehicle: ".$conn->error;


}


?>