<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include "../config.php";

$id = intval($_GET['id']);


// Get vehicle safely
$stmt = $conn->prepare(
    "SELECT * FROM vehicles WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

$stmt->close();


// Check vehicle exists
if(!$vehicle){
    die("Vehicle not found.");
}


if(isset($_POST['update'])){

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Keep old image by default
    $image = $vehicle['image'];


    // Check if a new image was selected
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){

        $uploaded_image = $_FILES['image'];


        // Check upload error
        if($uploaded_image['error'] !== UPLOAD_ERR_OK){

            die("Image upload failed.");

        }


        // Maximum size: 5 MB
        $max_size = 5 * 1024 * 1024;

        if($uploaded_image['size'] > $max_size){

            die("Image is too large. Maximum size is 5 MB.");

        }


        // Allowed image types
        $allowed_types = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/avif'
        ];


        // Check real file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $file_type = finfo_file(
            $finfo,
            $uploaded_image['tmp_name']
        );

        finfo_close($finfo);


        if(!in_array($file_type, $allowed_types)){

            die("Invalid image type. Please upload JPG, PNG, WEBP or AVIF.");

        }


        // Get extension
        $extension = pathinfo(
            $uploaded_image['name'],
            PATHINFO_EXTENSION
        );


        // Create safe filename
        $new_filename =
            uniqid('vehicle_', true)
            . '.'
            . strtolower($extension);


        $upload_path =
            "../assets/images/"
            . $new_filename;


        // Upload new image
        if(!move_uploaded_file(
            $uploaded_image['tmp_name'],
            $upload_path
        )){

            die("Failed to save image.");

        }


        // Delete old image
        if(!empty($vehicle['image'])){

            $old_image_path =
                "../assets/images/"
                . $vehicle['image'];


            if(file_exists($old_image_path)){

                unlink($old_image_path);

            }

        }


        // Save new filename
        $image = $new_filename;

    }


    // Update database safely
    $stmt = $conn->prepare(
        "UPDATE vehicles SET
         name = ?,
         price = ?,
         image = ?,
         description = ?,
         status = ?
         WHERE id = ?"
    );


    $stmt->bind_param(
        "sssssi",
        $name,
        $price,
        $image,
        $description,
        $status,
        $id
    );


    if($stmt->execute()){

        $stmt->close();
    
        $_SESSION['success'] = "Vehicle updated successfully!";
    
        header("Location: vehicles.php");
        exit();
    
    }
    else{

        echo "Error updating vehicle: "
             . $stmt->error;

    }

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Edit Vehicle</title>

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

input, textarea, select{
width:100%;
padding:12px;
margin:10px 0;
box-sizing:border-box;
}

button{
background:#0B1F3A;
color:white;
padding:12px 20px;
border:none;
cursor:pointer;
}

.current-image{
width:150px;
height:100px;
object-fit:cover;
border-radius:8px;
display:block;
margin:10px 0;
}

</style>

</head>

<body>

<form method="POST" enctype="multipart/form-data">

<h2>Edit Vehicle</h2>


<label>Vehicle Name</label>

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($vehicle['name']); ?>"
required>


<label>Price</label>

<input
type="text"
name="price"
value="<?php echo htmlspecialchars($vehicle['price']); ?>"
required>


<label>Current Vehicle Image</label>

<img
class="current-image"
src="../assets/images/<?php echo htmlspecialchars($vehicle['image']); ?>"
alt="<?php echo htmlspecialchars($vehicle['name']); ?>">


<label>Change Vehicle Image</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp,.avif">


<p>
Current file:
<?php echo htmlspecialchars($vehicle['image']); ?>
</p>


<label>Description</label>

<textarea
name="description"
rows="5"><?php echo htmlspecialchars($vehicle['description']); ?></textarea>


<label>Status</label>

<select name="status">

<option
value="Available"
<?php if($vehicle['status']=="Available") echo "selected"; ?>>
Available
</option>

<option
value="Booked"
<?php if($vehicle['status']=="Booked") echo "selected"; ?>>
Booked
</option>

<option
value="Maintenance"
<?php if($vehicle['status']=="Maintenance") echo "selected"; ?>>
Maintenance
</option>

</select>


<button name="update">
Update Vehicle
</button>

</form>

</body>

</html>