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

        <select name="vehicle" required>

            <option value="">Choose Vehicle</option>

            <option>Toyota Corolla Altis</option>
            <option>Hyundai Tucson</option>
            <option>Kia Sorento</option>
            <option>Hyundai H-1 Van</option>

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