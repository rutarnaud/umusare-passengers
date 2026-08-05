<?php

include "../config.php";


if(isset($_POST['id']) && isset($_POST['status'])){


    $id = $_POST['id'];
    $status = $_POST['status'];


    $sql = "UPDATE bookings 
            SET status='$status'
            WHERE id='$id'";


    if($conn->query($sql) === TRUE){

        header("Location: index.php");
        exit();

    }else{

        echo "Error: " . $conn->error;

    }


}else{


    echo "Missing data";


}


$conn->close();

?>