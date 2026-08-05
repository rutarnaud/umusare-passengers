<?php

// Database connection

$servername = "localhost";
$username = "root";
$password = "";
$database = "umusare_db";


// Create connection

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);


// Check connection

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}


// Get form data

$name = $_POST['name'];

$phone = $_POST['phone'];

$email = $_POST['email'];

$vehicle = $_POST['vehicle'];

$pickup_date = $_POST['pickup_date'];

$return_date = $_POST['return_date'];

$service = $_POST['service'];

$message = $_POST['message'];



// Insert data

$sql = "INSERT INTO bookings

(name, phone, email, vehicle, pickup_date, return_date, service, message)

VALUES

('$name',
'$phone',
'$email',
'$vehicle',
'$pickup_date',
'$return_date',
'$service',
'$message')";



if ($conn->query($sql) === TRUE) {


    echo "

    <h2>Booking Submitted Successfully!</h2>

    <p>Thank you $name. We will contact you soon.</p>

    <a href='index.php'>Back Home</a>

    ";


} else {


    echo "Error: " . $conn->error;


}



$conn->close();


?>