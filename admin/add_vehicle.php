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


    // Secure image upload

$allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];

$max_size = 5 * 1024 * 1024; // 5 MB

$image = $_FILES['image'];


// Check upload error

if($image['error'] !== UPLOAD_ERR_OK){

    die("Image upload failed.");

}


// Check file size

if($image['size'] > $max_size){

    die("Image is too large. Maximum size is 5 MB.");

}


// Check real file type

$finfo = finfo_open(FILEINFO_MIME_TYPE);

$file_type = finfo_file($finfo, $image['tmp_name']);

finfo_close($finfo);


if(!in_array($file_type, $allowed_types)){

    die("Invalid image type. Please upload JPG, PNG, WEBP or AVIF.");

}


// Create a safe filename

$extension = pathinfo($image['name'], PATHINFO_EXTENSION);

$new_filename = uniqid('vehicle_', true) . '.' . strtolower($extension);


$upload_path = "../assets/images/" . $new_filename;


// Move image

if(!move_uploaded_file($image['tmp_name'], $upload_path)){

    die("Failed to save image.");

}


$image = $new_filename;



$stmt = $conn->prepare(
    "INSERT INTO vehicles (name, price, image, description)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssss",
    $name,
    $price,
    $image,
    $description
);


if($stmt->execute()){

    $stmt->close();

    $_SESSION['success'] = "Vehicle added successfully!";

    header("Location: vehicles.php");
    exit();

}
else{

    echo "Error: ".$stmt->error;

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