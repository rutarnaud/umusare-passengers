<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include "../config.php";


$id = $_GET['id'];


$result = $conn->query(
"SELECT * FROM vehicles WHERE id=$id"
);


$vehicle = $result->fetch_assoc();



if(isset($_POST['update'])){


$name = $_POST['name'];
$price = $_POST['price'];
$image = $vehicle['image'];


if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){


    $image = $_FILES['image']['name'];

    $tmp_name = $_FILES['image']['tmp_name'];


    move_uploaded_file(
        $tmp_name,
        "../assets/images/".$image
    );


}
$description = $_POST['description'];
$status = $_POST['status'];



$sql = "UPDATE vehicles SET

name='$name',
price='$price',
image='$image',
description='$description',
status='$status'

WHERE id=$id";



if($conn->query($sql)){

header("Location: vehicles.php");
exit();

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

}


button{

background:#0B1F3A;
color:white;
padding:12px 20px;
border:none;

}

</style>


</head>


<body>


<form method="POST" enctype="multipart/form-data">


<h2>Edit Vehicle</h2>


<input 
type="text"
name="name"
value="<?php echo $vehicle['name']; ?>">



<input 
type="text"
name="price"
value="<?php echo $vehicle['price']; ?>">



<label>Change Vehicle Image</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png,.webp,.avif">


<p>
Current Image:
<?php echo $vehicle['image']; ?>
</p>type="text"
name="image"
value="<?php echo $vehicle['image']; ?>">



<textarea name="description">

<?php echo $vehicle['description']; ?>

</textarea>



<select name="status">


<option 
<?php if($vehicle['status']=="Available") echo "selected"; ?>>
Available
</option>


<option 
<?php if($vehicle['status']=="Booked") echo "selected"; ?>>
Booked
</option>


<option 
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