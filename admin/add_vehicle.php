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
// Save Vehicle
// ========================================

if(isset($_POST['save'])){


    // ====================================
    // Get Form Data
    // ====================================

    $name = trim($_POST['name'] ?? "");
    $price = trim($_POST['price'] ?? "");
    $description = trim($_POST['description'] ?? "");


    // ====================================
    // Validate Vehicle Name
    // ====================================

    if($name === ""){

        die("Vehicle name is required.");

    }


    // ====================================
    // Validate Price
    // ====================================

    if($price === "" || !is_numeric($price) || $price < 0){

        die("Please enter a valid vehicle price.");

    }


    // ====================================
    // Check Image
    // ====================================

    if(
        !isset($_FILES['image']) ||
        $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE
    ){

        die("Please select a vehicle image.");

    }


    $image = $_FILES['image'];


    // ====================================
    // Check Upload Error
    // ====================================

    if($image['error'] !== UPLOAD_ERR_OK){

        die("Image upload failed.");

    }


    // ====================================
    // Maximum File Size
    // ====================================

    $max_size = 5 * 1024 * 1024;


    if($image['size'] > $max_size){

        die(
            "Image is too large. Maximum size is 5 MB."
        );

    }


    // ====================================
    // Allowed MIME Types
    // ====================================

    $allowed_types = [

        'image/jpeg' => 'jpg',

        'image/png' => 'png',

        'image/webp' => 'webp',

        'image/avif' => 'avif'

    ];


    // ====================================
    // Check Real File Type
    // ====================================

    $finfo = finfo_open(FILEINFO_MIME_TYPE);


    if(!$finfo){

        die("Unable to verify image type.");

    }


    $file_type = finfo_file(
        $finfo,
        $image['tmp_name']
    );


    finfo_close($finfo);


    // ====================================
    // Validate MIME Type
    // ====================================

    if(!array_key_exists(
        $file_type,
        $allowed_types
    )){

        die(
            "Invalid image type. Please upload JPG, PNG, WEBP or AVIF."
        );

    }


    // ====================================
    // Get Extension From MIME Type
    // ====================================

    $extension = $allowed_types[$file_type];


    // ====================================
    // Create Safe Filename
    // ====================================

    $new_filename =
        uniqid('vehicle_', true)
        . '.'
        . $extension;


    $upload_path =
        "../assets/images/"
        . $new_filename;


    // ====================================
    // Move Image
    // ====================================

    if(!move_uploaded_file(
        $image['tmp_name'],
        $upload_path
    )){

        die("Failed to save image.");

    }


    // ====================================
    // Insert Vehicle Safely
    // ====================================

    $stmt = $conn->prepare(
        "INSERT INTO vehicles
        (name, price, image, description)
        VALUES (?, ?, ?, ?)"
    );


    if(!$stmt){

        // Remove uploaded image if
        // database statement cannot be prepared.

        if(file_exists($upload_path)){

            unlink($upload_path);

        }

        die(
            "Database error: "
            . $conn->error
        );

    }


    $stmt->bind_param(
        "ssss",
        $name,
        $price,
        $new_filename,
        $description
    );


    // ====================================
    // Execute Insert
    // ====================================

    if($stmt->execute()){


        $stmt->close();


        $_SESSION['success'] =
            "Vehicle added successfully!";


        header("Location: vehicles.php");

        exit();


    }else{


        // Remove image if database insert fails.

        if(file_exists($upload_path)){

            unlink($upload_path);

        }


        echo "Error adding vehicle: "
             . htmlspecialchars($stmt->error);


        $stmt->close();

    }

}

?>


<!DOCTYPE html>

<html>

<head>

<title>Add Vehicle | Umusare Passengers</title>


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


input,
textarea{

    width:100%;

    padding:12px;

    margin:10px 0;

    box-sizing:border-box;

}


button{

    padding:12px 20px;

    background:#0B1F3A;

    color:white;

    border:none;

    cursor:pointer;

}


button:hover{

    opacity:.9;

}


</style>

</head>


<body>


<form
method="POST"
enctype="multipart/form-data"
>


<h2>
Add New Vehicle
</h2>


<!-- ================================
     Vehicle Name
================================ -->


<label>
Vehicle Name
</label>


<input
type="text"
name="name"
placeholder="Vehicle Name"
required
>


<!-- ================================
     Price
================================ -->


<label>
Price
</label>


<input
type="number"
name="price"
placeholder="Price"
min="0"
step="0.01"
required
>


<!-- ================================
     Image
================================ -->


<label>
Vehicle Image
</label>


<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp,.avif"
required
>


<p>
Maximum image size: 5 MB
</p>


<!-- ================================
     Description
================================ -->


<label>
Description
</label>


<textarea
name="description"
placeholder="Description"
rows="5"
></textarea>


<!-- ================================
     Save
================================ -->


<button
type="submit"
name="save"
>

Save Vehicle

</button>


</form>


</body>

</html>