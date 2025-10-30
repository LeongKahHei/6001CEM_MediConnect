<?php
session_start();
if (!isset($_SESSION['id'])) {
    // No session ID — redirect to login page
    header("Location: ../registration/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Doctor Appointment Booking</title>
    <link rel="stylesheet" href="homepage.css">
</head>

<body>

    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="homepage.php" class="link active">Home</a></li>
                <li><a href="doctorSearch.php" class="link">Find doctor</a></li>
                <li><a href="patientBooking.php" class="link">My bookings</a></li>
                <li><a href="patientSetting.php" class="link">My profile</a></li>
            </ul>
        </div>
        <a href="../logout/logout.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>


    <header>
        <div class="container">
            <h1>Welcome to MediConnect</h1>
            <p>Book appointments with doctors easily and efficiently.</p>
            <a href="doctorSearch.php" class="btn">Search for doctors</a>
        </div>

        <section class="about">
            <div class="container">
                <h2>About MediConnect</h2>
                <p>
                    MediConnect is an online platform that bridges the gap between patients and healthcare
                    professionals.
                    Easily search for doctors by specialty, view their availability, and book appointments—all in one
                    place.
                </p>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2>How It Works</h2>
                <div class="steps">
                    <div class="step">
                        <h3>1. Search</h3>
                        <p>Find doctors by specialty or name.</p>
                    </div>
                    <div class="step">
                        <h3>2. Book</h3>
                        <p>Select your preferred time slot and confirm your booking.</p>
                    </div>
                    <div class="step">
                        <h3>3. Attend</h3>
                        <p>Visit your doctor at the scheduled time.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="benefits">
            <div class="container">
                <h2>Why Choose MediConnect?</h2>
                <ul>
                    <li>✔️ Easy and fast booking process</li>
                    <li>✔️ Verified and qualified doctors</li>
                </ul>
            </div>
        </section>
    </header>

    <footer>
        <div class="container">
            <p>&copy; 2025 MediConnect .</p>
        </div>
    </footer>

    <script>
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', function () {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop) {
                // User is scrolling down
                navbar.style.top = '-100px'; // Hide the navbar
            } else {
                // User is scrolling up
                navbar.style.top = '0'; // Show the navbar
            }
            lastScrollTop = scrollTop;
        });

    </script>

</body>

</html>