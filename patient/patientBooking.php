<?php
session_start();
if (!isset($_SESSION['id'])) {
    // No session ID — redirect to login page
    header("Location: ../registration/login.php");
    exit();
}

// Database connection
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Booking</title>
    <link rel="stylesheet" href="patientBooking.css">
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="homepage.php" class="link">Home</a></li>
                <li><a href="doctorSearch.php" class="link">Find doctor</a></li>
                <li><a href="patientBooking.php" class="link active">My bookings</a></li>
                <li><a href="patientSetting.php" class="link">My profile</a></li>
            </ul>
        </div>
        <a href="../logout/logout.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>

    <header>
        <div class="container">
            <h1>My Bookings</h1>
            <p>View the details of your upcoming sessions</p>
        </div>
    </header>

    <section id="booking-info" class="container">
        <h2>Booking Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Booking Date</th>
                    <th>Booking time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT users_doctor.username, booking.booking_date, booking.booking_time, booking.status
                        FROM booking
                        JOIN users_doctor ON booking.doctor_id = users_doctor.id
                        WHERE booking.patient_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("s", $_SESSION['id']);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['booking_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<p>No Bookings found</p>";
                }
                ?>
            </tbody>
        </table>
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

    </script>
</body>

</html>