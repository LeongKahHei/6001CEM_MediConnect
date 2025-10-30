<?php
session_start();
if (!isset($_SESSION['id'])) {
    // No session ID — redirect to login page
    header("Location: ../registration/login.php");
    exit();
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$doctor_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Bookings</title>
    <link rel="stylesheet" href="doctorBooking.css">

</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="doctorTime.php" class="link">Timeslot</a></li>
                <li><a href="doctorBooking.php" class="link active">Bookings</a></li>
                <li><a href="doctorSetting.php" class="link">Profile Setting</a></li>
            </ul>
        </div>
        <div class="nav-button">
            <a href="../logout/logout.php">
                <button class="btn" id="logoutBtn">Log Out</button>
            </a>
        </div>
    </nav>

    <header>
        <div class="container">
            <h1>My Bookings</h1>
            <p>View and manage your appointment requests</p>
        </div>
    </header>

    <section id="booking-info" class="container">
        <div class="tab-buttons">
            <button id="pendingBtn" class="active" onclick="loadBookings('pending')">Pending Bookings</button>
            <button id="acceptedBtn" onclick="loadBookings('accepted')">Accepted Bookings</button>
        </div>

        <div id="bookingTableContainer">
            <!-- Booking table will be loaded here with AJAX -->
        </div>
    </section>

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


        document.addEventListener('DOMContentLoaded', function () {
            loadBookings('pending'); // Default tab
        });

        function loadBookings(type) {
            document.getElementById('pendingBtn').classList.toggle('active', type === 'pending');
            document.getElementById('acceptedBtn').classList.toggle('active', type === 'accepted');

            fetch('fetchBookings.php?type=' + type)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('bookingTableContainer').innerHTML = data;
                })
                .catch(err => console.error('Error loading bookings:', err));
        }

        // Function reused for Accept/Reject buttons
        function updateBookingStatus(bookingId, status) {
            fetch('updateBookingStatus.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `booking_id=${bookingId}&status=${status}`
            })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        const row = document.getElementById(`booking-${bookingId}`);
                        if (row) {
                            row.classList.add('fade-out');
                            setTimeout(() => row.remove(), 500);
                        }
                    } else {
                        alert('Error: ' + data);
                    }
                })
                .catch(err => console.error('Update failed:', err));
        }
    </script>
</body>

</html>