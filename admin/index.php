<?php

include "../config.php";


$sql = "SELECT * FROM bookings ORDER BY id DESC";

$result = $conn->query($sql);


?>


<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard | Umusare Passengers</title>

<style>

body{

    font-family: Arial;
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


th, td{

    padding:12px;
    border:1px solid #ddd;
    text-align:center;

}

</style>

</head>


<body>


<h1>Umusare Passengers - Bookings</h1>


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



<?php

while($row = $result->fetch_assoc()){


?>


<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td><?php echo $row['vehicle']; ?></td>

<td><?php echo $row['pickup_date']; ?></td>

<td><?php echo $row['return_date']; ?></td>

<td><?php echo $row['service']; ?></td>
<td>
<?php echo htmlspecialchars($row['status']); ?>
</td>

<form action="update_status.php" method="POST">

<input type="hidden" 
name="id" 
value="<?php echo $row['id']; ?>">


<select name="status">

<option>
Pending
</option>

<option>
Confirmed
</option>

<option>
Cancelled
</option>

</select>


</td>


<td>

<button type="submit">
Update
</button>

</form>

</td>

</tr>


<?php

}

?>


</table>


</body>

</html>