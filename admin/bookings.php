<?php
// Database connection
session_start();
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$status = isset($_GET['status']) ? $_GET['status'] : 'all';

if ($status === 'all' || empty($status)) {
    $sql = "SELECT booking.id, users_patient.username AS patient_username,
            users_doctor.username AS doctor_username, booking.booking_date, 
            booking.booking_time, booking.status
            FROM booking
            JOIN users_patient ON booking.patient_id = users_patient.id
            JOIN users_doctor  ON booking.doctor_id  = users_doctor.id
            ORDER BY booking.booking_date ASC";

    $stmt = $db->prepare($sql);
} else {
    $sql = "SELECT booking.id, users_patient.username AS patient_username,
            users_doctor.username AS doctor_username, booking.booking_date, 
            booking.booking_time, booking.status
            FROM booking
            JOIN users_patient ON booking.patient_id = users_patient.id
            JOIN users_doctor  ON booking.doctor_id  = users_doctor.id
            WHERE booking.status = ?
            ORDER BY booking.booking_date ASC";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $status);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Booking</title>
    <link rel="stylesheet" href="bookings.css">
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="admin.php" class="link">All accounts</a></li>
                <li><a href="bookings.php" class="link active">All bookings</a></li>
                <li><a href="regisDoc.php" class="link">Doctor register</a></li>
            </ul>
        </div>
        <a href="../registration/login.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>

    <header>
        <div class="container">
            <h1>All Bookings</h1>
        </div>
    </header>

    <div class="container1">
        <aside class="sidebar">
            <h2>Filter by status</h2>
            <ul class="status-filter">
                <li><a href="?status=all" class="<?php echo ($status === 'all') ? 'active' : ''; ?>">all</a></li>
                <li><a href="?status=pending" class="<?php echo ($status === 'pending') ? 'active' : ''; ?>">pending</a>
                </li>
                <li><a href="?status=accepted"
                        class="<?php echo ($status === 'accepted') ? 'active' : ''; ?>">accepted</a></li>
                <li><a href="?status=rejected"
                        class="<?php echo ($status === 'rejected') ? 'active' : ''; ?>">rejected</a></li>
                <li><a href="?status=cancelled"
                        class="<?php echo ($status === 'cancelled') ? 'active' : ''; ?>">cancelled</a></li>

            </ul>
        </aside>
        <section id="booking-info" class="container">
            <h2>Booking Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Booking Date</th>
                        <th>Booking time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['patient_username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['doctor_username']) . "</td>";
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
    </div>


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