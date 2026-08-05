<!DOCTYPE html>
<html>
<head>

    <title>Book Your Vehicle | Umusare Passengers</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<section class="booking-section">

    <h1>Book Your Vehicle</h1>

    <form action="process_booking.php" method="POST">


        <label>Full Name</label>
        <input type="text" name="name" required>


        <label>Phone Number</label>
        <input type="text" name="phone" required>


        <label>Email</label>
        <input type="email" name="email">


        <label>Select Vehicle</label>

        <?php

$selectedVehicle = "";

if(isset($_GET['vehicle'])){

    $selectedVehicle = $_GET['vehicle'];

}

?>

<label>Select Vehicle</label>

<select name="vehicle" required>

<option value="">Choose Vehicle</option>

<option 
<?php if($selectedVehicle=="Toyota Corolla Altis") echo "selected"; ?>>
Toyota Corolla Altis
</option>

<option 
<?php if($selectedVehicle=="Hyundai Tucson") echo "selected"; ?>>
Hyundai Tucson
</option>

<option 
<?php if($selectedVehicle=="Kia Sorento") echo "selected"; ?>>
Kia Sorento
</option>

<option 
<?php if($selectedVehicle=="Hyundai H-1 Van") echo "selected"; ?>>
Hyundai H-1 Van
</option>

</select>


        <label>Pick-up Date</label>
        <input type="date" name="pickup_date" required>


        <label>Return Date</label>
        <input type="date" name="return_date" required>


        <label>Service Type</label>

        <select name="service" required>

            <option value="">Choose Service</option>
            <option>Self Drive</option>
            <option>With Driver</option>

        </select>


        <label>Message</label>

        <textarea name="message"></textarea>


        <button type="submit">
            Submit Booking
        </button>


    </form>


</section>


</body>
</html>