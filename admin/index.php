<?php

session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");

    exit();

}

include "../config.php";


// ================================
// Booking Statistics
// ================================

$totalBookings = $conn->query(
    "SELECT COUNT(*) AS total FROM bookings"
)->fetch_assoc()['total'];


$pendingBookings = $conn->query(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE status='Pending'"
)->fetch_assoc()['total'];


$confirmedBookings = $conn->query(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE status='Confirmed'"
)->fetch_assoc()['total'];


$cancelledBookings = $conn->query(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE status='Cancelled'"
)->fetch_assoc()['total'];


// ================================
// Get Bookings
// ================================

$sql = "SELECT *
        FROM bookings
        ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>

<title>Admin Dashboard | Umusare Passengers</title>

<style>

body{

    font-family:Arial;

    padding:40px;

    background:#f5f5f5;

}


h1{

    color:#0B1F3A;

}


table{

    width:100%;

    background:white;

    border-collapse:collapse;

}


th{

    background:#0B1F3A;

    color:white;

}


th,
td{

    padding:12px;

    border:1px solid #ddd;

    text-align:center;

}


.stats{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:20px;

    margin:30px 0;

}


.card{

    background:white;

    padding:25px;

    text-align:center;

    border-radius:10px;

    box-shadow:0 5px 15px rgba(0,0,0,.1);

}


.card h2{

    font-size:35px;

    color:#D4AF37;

}


.card p{

    color:#0B1F3A;

    font-weight:bold;

}


button{

    padding:8px 15px;

    cursor:pointer;

}


select{

    padding:7px;

}
.status-badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:20px;
    font-weight:bold;
}

.status-pending{
    background:#fff3cd;
    color:#856404;
}

.status-confirmed{
    background:#d4edda;
    color:#155724;
}

.status-cancelled{
    background:#f8d7da;
    color:#721c24;
}

</style>

</head>


<body>


<h1>
Umusare Passengers - Bookings
</h1>


<a href="logout.php">

<button>
Logout
</button>

</a>


<a href="vehicles.php">

<button>
Manage Vehicles
</button>

</a>


<!-- ================================
     Statistics
================================ -->

<div class="stats">


<div class="card">

<h2>
<?php echo $totalBookings; ?>
</h2>

<p>
Total Bookings
</p>

</div>


<div class="card">

<h2>
<?php echo $pendingBookings; ?>
</h2>

<p>
Pending
</p>

</div>


<div class="card">

<h2>
<?php echo $confirmedBookings; ?>
</h2>

<p>
Confirmed
</p>

</div>


<div class="card">

<h2>
<?php echo $cancelledBookings; ?>
</h2>

<p>
Cancelled
</p>

</div>


</div>


<!-- ================================
     Bookings Table
================================ -->

<table>


<tr>

<th>ID</th>

<th>Name</th>

<th>Phone</th>

<th>Vehicle</th>

<th>Pickup</th>

<th>Return</th>

<th>Service</th>

<th>Status</th>

<th>Action</th>

</tr>


<?php while($row = $result->fetch_assoc()){ ?>


<tr>


<td>
<?php echo $row['id']; ?>
</td>


<td>
<?php echo htmlspecialchars($row['name']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['phone']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['vehicle']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['pickup_date']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['return_date']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['service']); ?>
</td>


<td>

<?php

if($row['status'] == "Confirmed"){

    echo '<span class="status-badge status-confirmed">
            🟢 Confirmed
          </span>';

}elseif($row['status'] == "Cancelled"){

    echo '<span class="status-badge status-cancelled">
            🔴 Cancelled
          </span>';

}else{

    echo '<span class="status-badge status-pending">
            🟡 Pending
          </span>';

}

?>

</td>


<td>


<form
action="update_status.php"
method="POST"
>


<input
type="hidden"
name="id"
value="<?php echo $row['id']; ?>"
>


<select name="status">


<option
value="Pending"
<?php
if($row['status']=="Pending"){
    echo "selected";
}
?>
>
Pending
</option>


<option
value="Confirmed"
<?php
if($row['status']=="Confirmed"){
    echo "selected";
}
?>
>
Confirmed
</option>


<option
value="Cancelled"
<?php
if($row['status']=="Cancelled"){
    echo "selected";
}
?>
>
Cancelled
</option>


</select>


<button type="submit">
Update
</button>


</form>


</td>


</tr>


<?php } ?>


</table>


</body>

</html>