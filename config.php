<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "umusare_db";


$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);


if ($conn->connect_error) {

    die("Database connection failed: " . $conn->connect_error);

}


// Support UTF-8 / emojis

$conn->set_charset("utf8mb4");

?>