<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include "../config.php";

if(isset($_POST['save'])){

$name = $_POST['name'];
$price = $_POST['price'];
$image = $_POST['image'];
$description = $_POST['description'];

$sql = "INSERT INTO vehicles(name,price,image,description)
VALUES('$name','$price','$image','$description')";

if($conn->query($sql)){
    header("Location: vehicles.php");
    exit();
}else{
    echo "Error: ".$conn->error;
}

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Vehicle</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
padding:40px;
}

form{
background:white;
padding:30px;
max-width:500px;
margin:auto;
border-radius:10px;
}

input,textarea{
width:100%;
padding:12px;
margin:10px 0;
}

button{
padding:12px 20px;
background:#0B1F3A;
color:white;
border:none;
cursor:pointer;
}

</style>

</head>

<body>

<form method="POST">

<h2>Add New Vehicle</h2>

<input
type="text"
name="name"
placeholder="Vehicle Name"
required>

<input
type="text"
name="price"
placeholder="Price"
required>

<input
type="text"
name="image"
placeholder="Image filename (example: altis.avif)"
required>

<textarea
name="description"
placeholder="Description"></textarea>

<button
name="save">
Save Vehicle
</button>

</form>

</body>

</html>