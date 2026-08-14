<?php

session_start();

if(!isset($_SESSION['admin'])){

    header("Location: login.php");
    exit();

}

include "../config.php";


// ================================
// Search & Filters
// ================================

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$pickupFilter = $_GET['pickup_date'] ?? '';


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
// Build Booking Query
// ================================

$sql = "SELECT *
        FROM bookings
        WHERE 1=1";

$params = [];
$types = '';


// Search Name / Phone / Vehicle

if($search !== ''){

    $sql .= "
        AND (
            name LIKE ?
            OR phone LIKE ?
            OR vehicle LIKE ?
        )
    ";

    $searchValue = "%" . $search . "%";

    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;

    $types .= "sss";
}


// Status Filter

if(
    $statusFilter === "Pending" ||
    $statusFilter === "Confirmed" ||
    $statusFilter === "Cancelled"
){

    $sql .= " AND status = ?";

    $params[] = $statusFilter;

    $types .= "s";
}


// Pickup Date Filter

if($pickupFilter !== ''){

    $sql .= " AND pickup_date = ?";

    $params[] = $pickupFilter;

    $types .= "s";
}


$sql .= " ORDER BY id DESC";


// ================================
// Prepared Statement
// ================================

$stmt = $conn->prepare($sql);


if(!empty($params)){

    $stmt->bind_param(
        $types,
        ...$params
    );

}


$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<title>
Admin Dashboard | Umusare Passengers
</title>


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


select,
input{

    padding:9px;

}


.filters{

    background:white;

    padding:20px;

    margin:25px 0;

    border-radius:10px;

    box-shadow:0 5px 15px rgba(0,0,0,.06);

}


.filters input{

    width:250px;

    margin-right:10px;

}


.filters select{

    margin-right:10px;

}


.search-btn{

    background:#0B1F3A;

    color:white;

    border:none;

    border-radius:5px;

}


.clear-btn{

    display:inline-block;

    padding:8px 15px;

    background:#777;

    color:white;

    text-decoration:none;

    border-radius:5px;

    margin-left:5px;

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


.no-results{

    text-align:center;

    padding:30px;

    color:#777;

    font-weight:bold;

}


@media(max-width:900px){

    body{

        padding:15px;

    }

    .stats{

        grid-template-columns:repeat(2,1fr);

    }

}


@media(max-width:600px){

    .stats{

        grid-template-columns:1fr;

    }

    .filters input{

        width:100%;

        margin-bottom:10px;

    }

    .filters select{

        width:100%;

        margin-bottom:10px;

    }

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
     Search & Filters
================================ -->


<div class="filters">


<form method="GET">


<input
type="text"
name="search"
placeholder="Search name, phone or vehicle..."
value="<?php echo htmlspecialchars($search); ?>"
>


<select name="status">


<option value="">
All Statuses
</option>


<option
value="Pending"
<?php
if($statusFilter === "Pending"){
    echo "selected";
}
?>
>
🟡 Pending
</option>


<option
value="Confirmed"
<?php
if($statusFilter === "Confirmed"){
    echo "selected";
}
?>
>
🟢 Confirmed
</option>


<option
value="Cancelled"
<?php
if($statusFilter === "Cancelled"){
    echo "selected";
}
?>
>
🔴 Cancelled
</option>


</select>


<input
type="date"
name="pickup_date"
value="<?php echo htmlspecialchars($pickupFilter); ?>"
>


<button
type="submit"
class="search-btn"
>
🔎 Search
</button>


<a
href="index.php"
class="clear-btn"
>
Clear
</a>


</form>


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


<?php if($result->num_rows > 0){ ?>


<?php while($row = $result->fetch_assoc()){ ?>


<tr>


<td>
<?php echo htmlspecialchars($row['id']); ?>
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


<a
href="view_booking.php?id=<?php echo $row['id']; ?>"
style="
display:inline-block;
padding:7px 12px;
background:#0B1F3A;
color:white;
text-decoration:none;
border-radius:5px;
margin-right:5px;
">

View Details

</a>


<form
action="update_status.php"
method="POST"
style="display:inline;"
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


<button
type="submit"
>
Update
</button>


</form>


</td>


</tr>


<?php } ?>


<?php }else{ ?>


<tr>

<td
colspan="9"
class="no-results"
>

🔍 No bookings found.

</td>

</tr>


<?php } ?>


</table>


</body>

</html>

<?php

$stmt->close();

$conn->close();

?>