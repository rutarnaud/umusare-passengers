<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Umusare Passengers | Car Rental in Rwanda</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- Navigation -->

<header>
    <nav class="navbar">

        <div class="logo">
            UMUSARE PASSENGERS
        </div>


        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Fleet</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>


        <button class="book-btn">
            Book Now
        </button>


        <div class="language">
            🇬🇧 EN | 🇫🇷 FR
        </div>

    </nav>
</header>



<!-- Hero Section -->

<section class="hero">

    <div class="hero-content">

        <h1>
            Drive Rwanda with Confidence
        </h1>


        <p>
            Reliable Self Drive, Chauffeur Services,
            Airport Transfers and Corporate Car Rental.
        </p>


        <button class="primary-btn">
            Book Now
        </button>


        <button class="secondary-btn">
            WhatsApp
        </button>


    </div>

</section>



<!-- Booking Section -->

<section class="booking">

<h2>
Quick Booking
</h2>


<form>

<input type="date">

<input type="date">


<select>

<option>
Select Vehicle
</option>

<option>
Toyota Corolla Altis
</option>

<option>
Hyundai Tucson
</option>

<option>
Kia Sorento
</option>

<option>
Hyundai H-1 Van
</option>

</select>


<button>
Search Availability
</button>


</form>

</section>
<?php
include "config.php";
?>

<!-- Featured Vehicles -->

<section class="featured">

    <h2>Our Featured Vehicles</h2>
    <p>Choose from our reliable fleet for business, family trips, or airport transfers.</p>

    <div class="vehicle-container">

<?php

$result = $conn->query("SELECT * FROM vehicles WHERE status='Available'");


while($row = $result->fetch_assoc()){

?>

<div class="vehicle-card">

<img src="assets/images/<?php echo $row['image']; ?>" 
alt="<?php echo $row['name']; ?>">


<div class="vehicle-info">

<h3>
<?php echo htmlspecialchars($row['name']); ?>
</h3>


<p>
<strong>Price:</strong>
<?php echo htmlspecialchars($row['price']); ?>
</p>


<p>
<?php echo htmlspecialchars($row['description']); ?>
</p>


<a href="#" class="details-btn">
View Details
</a>


<a href="booking.php?vehicle=<?php echo urlencode($row['name']); ?>" 
class="book-now-btn">
Book Now
</a>


</div>

</div>


<?php

}

?>

</div>

</section>
<!-- Statistics -->

<section class="stats">

    <div class="stats-container">

        <div class="stat-box">
            <h2>4+</h2>
            <p>Vehicles Available</p>
        </div>

        <div class="stat-box">
            <h2>100+</h2>
            <p>Happy Customers</p>
        </div>

        <div class="stat-box">
            <h2>5+</h2>
            <p>Years Experience</p>
        </div>

        <div class="stat-box">
            <h2>24/7</h2>
            <p>Customer Support</p>
        </div>

    </div>

</section>
<!-- Call To Action -->

<section class="cta">

    <div class="cta-content">

        <h2>Ready to Explore Rwanda?</h2>

        <p>
            Rent a reliable vehicle today and enjoy a safe, comfortable,
            and affordable journey with Umusare Passengers.
        </p>

        <div class="cta-buttons">

        <a href="booking.php" class="book-now-btn">
    Book Now
</a>

            <a href="https://wa.me/250788957060"
               class="cta-whatsapp"
               target="_blank">

                WhatsApp Us

            </a>

        </div>

    </div>

</section>
<!-- Testimonials -->

<section class="testimonials">

    <h2>What Our Customers Say</h2>

    <div class="testimonial-container">

        <div class="testimonial-card">

            <p>
                "Excellent service! The vehicle was clean,
                comfortable, and exactly what we needed."
            </p>

            <h4>⭐⭐⭐⭐⭐</h4>

            <h3>John D.</h3>

        </div>

        <div class="testimonial-card">

            <p>
                "Professional drivers and affordable prices.
                I highly recommend Umusare Passengers."
            </p>

            <h4>⭐⭐⭐⭐⭐</h4>

            <h3>Alice M.</h3>

        </div>

        <div class="testimonial-card">

            <p>
                "Booking was easy, and customer support
                responded quickly."
            </p>

            <h4>⭐⭐⭐⭐⭐</h4>

            <h3>Patrick K.</h3>

        </div>

    </div>

</section>

<!-- Footer -->

<!-- Professional Footer -->

<footer class="footer">

    <div class="footer-container">

        <!-- About -->
        <div class="footer-box">

            <h3>UMUSARE PASSENGERS</h3>

            <p>
                Reliable car rental services in Rwanda.
                Self drive, chauffeur services and airport transfers.
            </p>

        </div>


        <!-- Quick Links -->
        <div class="footer-box">

            <h3>Quick Links</h3>

            <ul>

                <li><a href="#">Home</a></li>
                <li><a href="#fleet">Fleet</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>

            </ul>

        </div>


        <!-- Contact -->
        <div class="footer-box">

            <h3>Contact Us</h3>

            <p>
                📍 Kigali, Rwanda
            </p>

            <p>
                📞 +250 788 957 060
            </p>

            <p>
                ✉ info@umusarepassengers.com
            </p>

        </div>


        <!-- Social Media -->
        <div class="footer-box">

            <h3>Follow Us</h3>

            <p>
                Facebook
            </p>

            <p>
                Instagram
            </p>

            <p>
                WhatsApp
            </p>

        </div>


    </div>


    <div class="footer-bottom">

        <p>
            © 2026 Umusare Passengers. All Rights Reserved.
        </p>

    </div>


</footer>



<script src="assets/js/script.js"></script>

</body>
</html>