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
// Success Message
// ========================================

$success = "";


if(isset($_SESSION['success'])){

    $success = $_SESSION['success'];

    unset($_SESSION['success']);

}


// ========================================
// Get Vehicles
// ========================================

$result = $conn->query(
    "SELECT *
     FROM vehicles
     ORDER BY id DESC"
);


if(!$result){

    die(
        "Unable to load vehicles: "
        . htmlspecialchars($conn->error)
    );

}

?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>
Vehicle Management | Umusare Passengers
</title>


<style>

*{

    box-sizing:border-box;

}


body{

    font-family:Arial, sans-serif;

    background:#f5f5f5;

    padding:30px;

    margin:0;

}


h1{

    color:#0B1F3A;

    margin-bottom:25px;

}


/* ========================================
   Buttons
======================================== */


.btn{

    display:inline-block;

    padding:10px 18px;

    text-decoration:none;

    border-radius:6px;

    margin-bottom:20px;

    margin-right:8px;

    font-weight:bold;

}


.add-btn{

    background:#D4AF37;

    color:#000;

}


.back-btn{

    background:#0B1F3A;

    color:white;

}


.edit-btn{

    display:inline-block;

    background:#0B1F3A;

    color:white;

    padding:7px 12px;

    border-radius:5px;

    text-decoration:none;

    margin-right:5px;

}


.delete-btn{

    display:inline-block;

    background:#dc3545;

    color:white;

    padding:7px 12px;

    border-radius:5px;

    text-decoration:none;

}


/* ========================================
   Success Message
======================================== */


.success-message{

    background:#d4edda;

    color:#155724;

    padding:15px;

    margin-bottom:20px;

    border-radius:6px;

    font-weight:bold;

}


/* ========================================
   Table
======================================== */


.table-container{

    width:100%;

    overflow-x:auto;

}


table{

    width:100%;

    border-collapse:collapse;

    background:white;

    min-width:750px;

}


th,
td{

    border:1px solid #ddd;

    padding:12px;

    text-align:center;

}


th{

    background:#0B1F3A;

    color:white;

}


/* ========================================
   Vehicle Image
======================================== */


.vehicle-image{

    width:120px;

    height:80px;

    object-fit:cover;

    border-radius:8px;

}


/* ========================================
   Status Badges
======================================== */


.status-badge{

    display:inline-block;

    padding:6px 12px;

    border-radius:20px;

    font-weight:bold;

}


.status-available{

    background:#d4edda;

    color:#155724;

}


.status-booked{

    background:#fff3cd;

    color:#856404;

}


.status-maintenance{

    background:#f8d7da;

    color:#721c24;

}


.status-unknown{

    background:#e2e3e5;

    color:#383d41;

}


/* ========================================
   Mobile
======================================== */


@media(max-width:700px){

    body{

        padding:15px;

    }

    h1{

        font-size:25px;

    }

}

</style>

</head>


<body>


<h1>
🚗 Vehicle Management
</h1>


<!-- ========================================
     Success Message
======================================== -->


<?php if($success != ""){ ?>

<div class="success-message">

    ✅
    <?php echo htmlspecialchars($success); ?>

</div>

<?php } ?>


<!-- ========================================
     Navigation
======================================== -->


<a
class="btn add-btn"
href="add_vehicle.php"
>
➕ Add New Vehicle
</a>


<a
class="btn back-btn"
href="index.php"
>
⬅ Back to Dashboard
</a>


<!-- ========================================
     Vehicles Table
======================================== -->


<div class="table-container">


<table>


<thead>

<tr>

<th>ID</th>

<th>Image</th>

<th>Name</th>

<th>Price</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php while($row = $result->fetch_assoc()){ ?>


<tr>


<!-- ID -->

<td>

<?php echo intval($row['id']); ?>

</td>


<!-- Image -->

<td>

<img
class="vehicle-image"
src="../assets/images/<?php
echo htmlspecialchars($row['image']);
?>"
alt="<?php
echo htmlspecialchars($row['name']);
?>"
>


</td>


<!-- Name -->

<td>

<?php
echo htmlspecialchars($row['name']);
?>

</td>


<!-- Price -->

<td>

<?php
echo htmlspecialchars($row['price']);
?>

</td>


<!-- Status -->

<td>


<?php

$status = $row['status'] ?? "Unknown";


if($status == "Available"){

    echo '
    <span class="status-badge status-available">
        🟢 Available
    </span>
    ';

}
elseif($status == "Booked"){

    echo '
    <span class="status-badge status-booked">
        🟡 Booked
    </span>
    ';

}
elseif($status == "Maintenance"){

    echo '
    <span class="status-badge status-maintenance">
        🔴 Maintenance
    </span>
    ';

}
else{

    echo '
    <span class="status-badge status-unknown">
        ⚪ Unknown
    </span>
    ';

}

?>


</td>


<!-- Actions -->

<td>


<a
class="edit-btn"
href="edit_vehicle.php?id=<?php
echo intval($row['id']);
?>"
>
✏️ Edit
</a>


<a
class="delete-btn"
href="delete_vehicle.php?id=<?php
echo intval($row['id']);
?>"
onclick="return confirm(
'Are you sure you want to delete this vehicle?'
);"
>
🗑️ Delete
</a>


</td>


</tr>


<?php } ?>


</tbody>


</table>


</div>


</body>

</html>