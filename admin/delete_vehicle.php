<?php

session_start();


if(!isset($_SESSION['admin'])){

    header("Location: login.php");

    exit();

}


include "../config.php";


$id = $_GET['id'];


$sql = "DELETE FROM vehicles WHERE id=$id";


if($conn->query($sql)){


header("Location: vehicles.php");


exit();


}else{


echo "Error deleting vehicle: ".$conn->error;


}

?>