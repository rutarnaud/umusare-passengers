<?php

session_start();


if(!isset($_SESSION['admin'])){

    header("Location: login.php");

    exit();

}


include "../config.php";


$id = $_GET['id'];


// First get vehicle image

$result = $conn->query(
    "SELECT image FROM vehicles WHERE id=$id"
);


$vehicle = $result->fetch_assoc();


$image = $vehicle['image'];


// Delete image from folder

if(!empty($image)){

    $image_path = "../assets/images/".$image;

    if(file_exists($image_path)){

        unlink($image_path);

    }

}


// Delete vehicle from database

$sql = "DELETE FROM vehicles WHERE id=$id";


if($conn->query($sql)){


    header("Location: vehicles.php");

    exit();


}else{


    echo "Error deleting vehicle: ".$conn->error;


}


?>