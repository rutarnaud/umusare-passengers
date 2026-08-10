<?php

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

include "../config.php";
$success = "";

if(isset($_SESSION['success'])){

    $success = $_SESSION['success'];

    unset($_SESSION['success']);

}

$result = $conn->query("SELECT * FROM vehicles ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Vehicles</title>

    <style>

    body{
        font-family:Arial;
        background:#f5f5f5;
        padding:30px;
    }

    h1{
        color:#0B1F3A;
    }

    table{
        width:100%;
        border-collapse:collapse;
        background:white;
    }

    th,td{
        border:1px solid #ddd;
        padding:12px;
        text-align:center;
    }

    th{
        background:#0B1F3A;
        color:white;
    }

    img{
    width:120px;
    height:80px;
    object-fit:cover;
    border-radius:8px;
}

    .btn{
        display:inline-block;
        padding:10px 18px;
        background:#D4AF37;
        color:black;
        text-decoration:none;
        border-radius:6px;
        margin-bottom:20px;
        font-weight:bold;
    }
    .success-message{
    background:#d4edda;
    color:#155724;
    padding:15px;
    margin-bottom:20px;
    border-radius:6px;
    font-weight:bold;
}

    </style>

</head>

<body>

<h1>Vehicle Management</h1>
<?php if($success != ""){ ?>

<div class="success-message">
    ✅ <?php echo htmlspecialchars($success); ?>
</div>

<?php } ?>
<a class="btn" href="add_vehicle.php">
➕ Add New Vehicle
</a>

<a class="btn" href="index.php">⬅ Back to Dashboard</a>

<table>

<tr>

<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Status</th>
<th>Action</th> 
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<img 
src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>"
alt="<?php echo htmlspecialchars($row['name']); ?>">
</td>

<td><?php echo htmlspecialchars($row['name']); ?></td>

<td><?php echo htmlspecialchars($row['price']); ?></td>

<td><?php echo htmlspecialchars($row['status']); ?></td>

<td>

<a href="edit_vehicle.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a 
href="delete_vehicle.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this vehicle?');">

Delete

</a>

</td>

</tr>


<?php } ?>

</table>

</body>
</html>