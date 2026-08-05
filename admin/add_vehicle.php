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
    $description = $_POST['description'];


    // Upload image

    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];


    $upload_path = "../assets/images/" . $image;


    move_uploaded_file($tmp_name, $upload_path);



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

<form method="POST" enctype="multipart/form-data">

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

<label>Vehicle Image</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp,.avif"
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