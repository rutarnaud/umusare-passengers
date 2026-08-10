<?php

include "config.php";

$selectedVehicle = "";

if(isset($_GET['vehicle'])){

    $selectedVehicle = $_GET['vehicle'];

}


// Get available vehicles

$result = $conn->query(
    "SELECT * FROM vehicles
     WHERE status = 'Available'
     ORDER BY name ASC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Book Your Vehicle | Umusare Passengers</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<section class="booking-section">

    <h1>Book Your Vehicle</h1>


    <form action="process_booking.php"
          method="POST">


        <!-- Full Name -->

        <label>Full Name</label>

        <input
            type="text"
            name="name"
            required>


        <!-- Phone -->

        <label>Phone Number</label>

        <input
            type="text"
            name="phone"
            required>


        <!-- Email -->

        <label>Email</label>

        <input
            type="email"
            name="email">


        <!-- Vehicle -->

        <label>Select Vehicle</label>

        <select name="vehicle" required>

            <option value="">
                Choose Vehicle
            </option>


            <?php while($row = $result->fetch_assoc()){ ?>

                <option
                    value="<?php echo htmlspecialchars($row['name']); ?>"
                    <?php
                    if($selectedVehicle == $row['name']){
                        echo "selected";
                    }
                    ?>
                >

                    <?php echo htmlspecialchars($row['name']); ?>

                </option>

            <?php } ?>


        </select>


        <!-- Pickup Date -->

        <label>Pick-up Date</label>

     <input
    type="date"
    name="pickup_date"
    id="pickup_date"
    min="<?php echo date('Y-m-d'); ?>"
    required>


        <!-- Return Date -->

        <label>Return Date</label>

     <input
    type="date"
    name="return_date"
    id="return_date"
    min="<?php echo date('Y-m-d'); ?>"
    required>

    <div id="availability-message"></div>
        <!-- Service -->

        <label>Service Type</label>

        <select name="service" required>

            <option value="">
                Choose Service
            </option>

            <option value="Self Drive">
                Self Drive
            </option>

            <option value="With Driver">
                With Driver
            </option>

        </select>


        <!-- Message -->

        <label>Message</label>

        <textarea
            name="message"
            placeholder="Additional message..."></textarea>


        <!-- Submit -->

        <button
    type="submit"
    id="submitBooking"
    disabled>
    Submit Booking
</button>

    </form>

</section>
<script>

const vehicleSelect = document.querySelector('select[name="vehicle"]');

const pickupDate = document.getElementById("pickup_date");

const returnDate = document.getElementById("return_date");

const availabilityMessage =
    document.getElementById("availability-message");
    
 const submitBooking =
    document.getElementById("submitBooking");


// ========================================
// Pickup Date → Return Date
// ========================================

pickupDate.addEventListener("change", function(){

    returnDate.min = pickupDate.value;

    if(returnDate.value < pickupDate.value){

        returnDate.value = pickupDate.value;

    }

    checkAvailability();

});


// ========================================
// Check Vehicle Availability
// ========================================

function checkAvailability(){

    const vehicle = vehicleSelect.value;

    const pickup = pickupDate.value;

    const returnDateValue = returnDate.value;


    if(!vehicle || !pickup || !returnDateValue){

availabilityMessage.innerHTML = "";

submitBooking.disabled = true;

return;

}


    availabilityMessage.innerHTML =
        "Checking availability...";


    fetch(
        "check_availability.php?vehicle="
        + encodeURIComponent(vehicle)
        + "&pickup_date="
        + encodeURIComponent(pickup)
        + "&return_date="
        + encodeURIComponent(returnDateValue)
    )

    .then(response => response.json())

    .then(data => {

        if(data.available){

availabilityMessage.innerHTML =
    "🟢 " + data.message;

submitBooking.disabled = false;

}else{

availabilityMessage.innerHTML =
    "🔴 " + data.message;

submitBooking.disabled = true;

}

    })

    .catch(error => {

        availabilityMessage.innerHTML =
            "⚠️ Unable to check availability.";

        console.error(error);

    });

}


// ========================================
// Vehicle Changed
// ========================================

vehicleSelect.addEventListener(
    "change",
    checkAvailability
);


// ========================================
// Return Date Changed
// ========================================

returnDate.addEventListener(
    "change",
    checkAvailability
);

</script>


</body>

</html>