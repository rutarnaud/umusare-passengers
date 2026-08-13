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
// Get Vehicle ID
// ========================================

$id = intval($_GET['id'] ?? 0);

if($id <= 0){

    die("Invalid vehicle ID.");

}


// ========================================
// Get Vehicle Safely
// ========================================

$stmt = $conn->prepare(
    "SELECT * FROM vehicles WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$vehicle = $result->fetch_assoc();

$stmt->close();


if(!$vehicle){

    die("Vehicle not found.");

}


// ========================================
// Update Vehicle
// ========================================

if(isset($_POST['update'])){


    // ====================================
    // Get Form Data
    // ====================================

    $name = trim($_POST['name'] ?? "");
    $price = trim($_POST['price'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $status = $_POST['status'] ?? "";


    // ====================================
    // Validate Name
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
    // Validate Status
    // ====================================

    $allowedStatuses = [
        "Available",
        "Booked",
        "Maintenance"
    ];


    if(!in_array($status, $allowedStatuses, true)){

        die("Invalid vehicle status.");

    }


    // ====================================
    // Keep Old Image
    // ====================================

    $image = $vehicle['image'];

    $new_image_uploaded = false;
    $new_image_path = "";
    $old_image_path = "";


    // ====================================
    // Check New Image
    // ====================================

    if(
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ){


        $uploaded_image = $_FILES['image'];


        // ==================================
        // Upload Error
        // ==================================

        if($uploaded_image['error'] !== UPLOAD_ERR_OK){

            die("Image upload failed.");

        }


        // ==================================
        // Maximum File Size
        // ==================================

        $max_size = 5 * 1024 * 1024;


        if($uploaded_image['size'] > $max_size){

            die(
                "Image is too large. Maximum size is 5 MB."
            );

        }


        // ==================================
        // Allowed MIME Types
        // ==================================

        $allowed_types = [

            'image/jpeg' => 'jpg',

            'image/png' => 'png',

            'image/webp' => 'webp',

            'image/avif' => 'avif'

        ];


        // ==================================
        // Real File Type
        // ==================================

        $finfo = finfo_open(FILEINFO_MIME_TYPE);


        if(!$finfo){

            die("Unable to verify image type.");

        }


        $file_type = finfo_file(
            $finfo,
            $uploaded_image['tmp_name']
        );


        finfo_close($finfo);


        // ==================================
        // Validate MIME Type
        // ==================================

        if(!array_key_exists(
            $file_type,
            $allowed_types
        )){

            die(
                "Invalid image type. Please upload JPG, PNG, WEBP or AVIF."
            );

        }


        // ==================================
        // Get Safe Extension From MIME
        // ==================================

        $extension = $allowed_types[$file_type];


        // ==================================
        // Create Safe Filename
        // ==================================

        $new_filename =
            uniqid('vehicle_', true)
            . '.'
            . $extension;


        $new_image_path =
            "../assets/images/"
            . $new_filename;


        // ==================================
        // Move New Image
        // ==================================

        if(!move_uploaded_file(
            $uploaded_image['tmp_name'],
            $new_image_path
        )){

            die("Failed to save image.");

        }


        $new_image_uploaded = true;

        $old_image_path =
            "../assets/images/"
            . $vehicle['image'];


        $image = $new_filename;

    }


    // ====================================
    // Update Database
    // ====================================

    $stmt = $conn->prepare(
        "UPDATE vehicles SET
         name = ?,
         price = ?,
         image = ?,
         description = ?,
         status = ?
         WHERE id = ?"
    );


    if(!$stmt){

        // If database update cannot be prepared,
        // remove newly uploaded image.

        if($new_image_uploaded && file_exists($new_image_path)){

            unlink($new_image_path);

        }

        die(
            "Database error: "
            . $conn->error
        );

    }


    $stmt->bind_param(
        "sssssi",
        $name,
        $price,
        $image,
        $description,
        $status,
        $id
    );


    // ====================================
    // Execute Update
    // ====================================

    if($stmt->execute()){


        $stmt->close();


        // =================================
        // Delete Old Image ONLY AFTER
        // Database Update Succeeds
        // =================================

        if(
            $new_image_uploaded &&
            !empty($vehicle['image']) &&
            file_exists($old_image_path)
        ){

            unlink($old_image_path);

        }


        $_SESSION['success'] =
            "Vehicle updated successfully!";


        header("Location: vehicles.php");

        exit();


    }else{


        // =================================
        // Database Failed
        // Remove New Image
        // =================================

        if(
            $new_image_uploaded &&
            file_exists($new_image_path)
        ){

            unlink($new_image_path);

        }


        echo "Error updating vehicle: "
             . htmlspecialchars($stmt->error);


        $stmt->close();

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Vehicle | Umusare Passengers</title>


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
textarea,
select{

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


button:hover{

    opacity:.9;

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


<form
method="POST"
enctype="multipart/form-data"
>


<h2>
Edit Vehicle
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
value="<?php echo htmlspecialchars($vehicle['name']); ?>"
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
value="<?php echo htmlspecialchars($vehicle['price']); ?>"
min="0"
step="0.01"
required
>


<!-- ================================
     Current Image
================================ -->


<label>
Current Vehicle Image
</label>


<?php if(!empty($vehicle['image'])){ ?>

<img
class="current-image"
src="../assets/images/<?php echo htmlspecialchars($vehicle['image']); ?>"
alt="<?php echo htmlspecialchars($vehicle['name']); ?>"
>

<?php }else{ ?>

<p>
No image available.
</p>

<?php } ?>


<!-- ================================
     New Image
================================ -->


<label>
Change Vehicle Image
</label>


<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp,.avif"
>


<p>
Maximum image size: 5 MB
</p>


<p>

Current file:

<?php echo htmlspecialchars($vehicle['image']); ?>

</p>


<!-- ================================
     Description
================================ -->


<label>
Description
</label>


<textarea
name="description"
rows="5"
><?php echo htmlspecialchars($vehicle['description']); ?></textarea>


<!-- ================================
     Status
================================ -->


<label>
Status
</label>


<select name="status" required>


<option
value="Available"
<?php

if($vehicle['status'] === "Available"){

    echo "selected";

}

?>
>

Available

</option>


<option
value="Booked"
<?php

if($vehicle['status'] === "Booked"){

    echo "selected";

}

?>
>

Booked

</option>


<option
value="Maintenance"
<?php

if($vehicle['status'] === "Maintenance"){

    echo "selected";

}

?>
>

Maintenance

</option>


</select>


<!-- ================================
     Update
================================ -->


<button
type="submit"
name="update"
>

Update Vehicle

</button>


</form>


</body>

</html>