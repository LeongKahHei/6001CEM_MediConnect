<?php
// Database connection
session_start();
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$doctor_id = $_SESSION['id'];

// Fetch existing availability for the doctor
$availability_data = [];
$sql = "SELECT day_of_week, start_time, end_time FROM doctor_availability WHERE doctor_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $availability_data[$row['day_of_week']] = [
        'start' => $row['start_time'],
        'end' => $row['end_time']
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Time</title>
    <link rel="stylesheet" href="doctorTime.css">
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="doctorTime.php" class="link active">Timeslot</a></li>
                <li><a href="doctorBooking.php" class="link">Bookings</a></li>
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
            <h1>My Timeslots</h1>
        </div>
    </header>

    <section id="timeslot-info" class="container">
        <h2>Set Weekly Availability</h2>
        <form action="saveTimeslot.php" method="POST">

            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day):
                $isAvailable = isset($availability_data[$day]);
                $startTime = $isAvailable ? $availability_data[$day]['start'] : '09:00';// 9am-5pm as default time 
                $endTime = $isAvailable ? $availability_data[$day]['end'] : '17:00';
                ?>
                <div class="day-block">
                    <label>
                        <input type="checkbox" name="availability[<?= $day ?>][available]" class="available-toggle"
                            <?= $isAvailable ? 'checked' : '' ?>>
                        <?= $day ?> Available
                    </label>

                    <div class="time-select" style="display: <?= $isAvailable ? 'block' : 'none' ?>;">
                        <label>Start Time:</label>
                        <select name="availability[<?= $day ?>][start]">
                            <?php
                            for ($h = 0; $h < 24; $h++) {
                                $time = sprintf("%02d:00", $h);
                                $selected = (substr($startTime, 0, 5) === $time) ? 'selected' : '';
                                //change value from 00:00 to 00:00:00 to match database
                                echo "<option value='$time' $selected>$time</option>";
                            }
                            ?>
                        </select>

                        <label>End Time:</label>
                        <select name="availability[<?= $day ?>][end]">
                            <?php
                            for ($h = 1; $h <= 24; $h++) {
                                $time = sprintf("%02d:00", $h % 24);
                                $selected = (substr($endTime, 0, 5) === $time) ? 'selected' : '';
                                echo "<option value='$time' $selected>$time</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn">Save Availability</button>
        </form>

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


        // Toggle visibility of time dropdowns when checkbox checked/unchecked
        document.querySelectorAll('.available-toggle').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const timeSelect = this.closest('.day-block').querySelector('.time-select');
                timeSelect.style.display = this.checked ? 'block' : 'none';
            });
        });

        style="display: <?= $isAvailable ? 'block' : 'none' ?>;"
    </script>
</body>

</html>