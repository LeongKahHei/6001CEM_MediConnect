<?php
session_start();
if (!isset($_SESSION['id'])) {
    // No session ID — redirect to login page
    header("Location: ../registration/login.php");
    exit();
}

//Database connection
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get doctor ID from query parameter
if (isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];

    // Query doctor information
    $sql = "SELECT * FROM users_doctor WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();

    // Check if doctor exists
    if (!$doctor) {
        echo "<p>doctor not found.</p>";
        exit();
    }

    // Fetch doctor availability
    $availability_sql = "SELECT day_of_week, start_time, end_time FROM doctor_availability WHERE doctor_id = ?";
    $stmt2 = $db->prepare($availability_sql);
    $stmt2->bind_param("i", $doctor_id);
    $stmt2->execute();
    $availability_result = $stmt2->get_result();

    $availability = [];
    while ($row = $availability_result->fetch_assoc()) {
        $availability[$row['day_of_week']] = [
            'start' => $row['start_time'],
            'end' => $row['end_time']
        ];
    }

} else {
    echo "<p>No doctor selected.</p>";
    exit();
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $patient_id = $_SESSION['id'];

    // Check if this date has availability
    $selected_day = date('l', strtotime($date)); // Convert date to the day name(monday)

    if (!isset($availability[$selected_day])) {//checks if that day exists in the doctor’s availability array from the database.
        echo "<script>alert('No availability on the selected date. Please choose another day.');</script>";
    } else {
        // Check if the patient already has a booking (any doctor)
        $check_patient_sql = "SELECT id FROM booking WHERE patient_id = ? AND status IN ('pending', 'accepted')";
        $check_stmt = $db->prepare($check_patient_sql);
        $check_stmt->bind_param("i", $patient_id);
        $check_stmt->execute();
        $check_patient_result = $check_stmt->get_result();

        if ($check_patient_result->num_rows > 0) {
            echo "<script>alert('You already have an active booking. You can only have one booking at a time.');</script>";
        } else {
            // Check if another patient has already booked this doctor for the same date & time
            $check_conflict_sql = "SELECT id FROM booking 
                                   WHERE doctor_id = ? AND booking_date = ? AND booking_time = ? 
                                   AND status IN ('pending', 'accepted')";
            $conflict_stmt = $db->prepare($check_conflict_sql);
            $conflict_stmt->bind_param("iss", $doctor_id, $date, $time);
            $conflict_stmt->execute();
            $conflict_result = $conflict_stmt->get_result();

            if ($conflict_result->num_rows > 0) {
                echo "<script>alert('This time slot is already booked. Please select another time.');</script>";
            } else {
                // if no error, inserting new booking with status as pending
                $insert_sql = "INSERT INTO booking (doctor_id, patient_id, booking_date, booking_time, status) 
                               VALUES (?, ?, ?, ?, 'pending')";
                $stmt = $db->prepare($insert_sql);
                $stmt->bind_param("iiss", $doctor_id, $patient_id, $date, $time);

                if ($stmt->execute()) {
                    echo "<script>alert('Booking confirmed! Status: Pending approval by doctor.'); window.location='patientBooking.php';</script>";
                } else {
                    echo "<script>alert('Error booking session. Please try again later.');</script>";
                }

                $stmt->close();
            }

            $conflict_stmt->close();
        }

        $check_stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>doctor Profile</title>
    <link rel="stylesheet" href="doctorProfile.css">
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
            <h1>Doctor Profile</h1>
        </div>
    </header>

    <!-- doctor Profile Section -->
    <section id="doctor-profile" class="container">
        <div class="profile-container">
            <!-- doctor Info Section -->
            <div class="doctor-details">
                <?php $profilePic = !empty($doctor['profile_picture']) ? $doctor['profile_picture'] : '../images/profile.png';
                echo "<img src='" . htmlspecialchars($profilePic) . "' alt='Doctor Photo'>"; ?>
                <h1><?php echo htmlspecialchars($doctor['username']); ?>'s Profile</h1>
                <p><strong>Specialty: </strong><?php echo htmlspecialchars($doctor['specialty']); ?></p>
                <p><strong>Experience: </strong><?php echo htmlspecialchars($doctor['experience']); ?></p>
                <p><strong>Languages: </strong><?php echo htmlspecialchars($doctor['languages']); ?></p>
                <p><strong>Phone number: </strong><?php echo htmlspecialchars($doctor['phone']); ?></p>
                <p><strong>Email: </strong><?php echo htmlspecialchars($doctor['email']); ?></p>
            </div>

            <div class="booking-section">
                <h3>Book a Session with <?php echo htmlspecialchars($doctor['username']); ?></h3>
                <form action="" method="POST" id="booking-form">

                    <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">

                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="time">Select Time:</label>
                    <select id="time" name="time" required>
                        <option>Please select date</option>
                    </select>

                    <button type="submit" class="btn" name="book">Confirm Booking</button>
                </form>
            </div>
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

        const availability = <?php echo json_encode($availability); ?>;

        document.getElementById('date').addEventListener('change', function () {
            const date = new Date(this.value);
            const weekday = date.toLocaleDateString('en-US', { weekday: 'long' });
            const timeSelect = document.getElementById('time');
            timeSelect.innerHTML = '';

            if (availability[weekday]) {
                // Add default "Select Time" option only when available
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '-- Select Time --';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                timeSelect.appendChild(defaultOption);

                const start = availability[weekday].start;
                const end = availability[weekday].end;
                const startHour = parseInt(start.split(':')[0]);
                const endHour = parseInt(end.split(':')[0]);

                for (let hour = startHour; hour < endHour; hour++) {
                    const option = document.createElement('option');
                    option.value = `${hour}:00`;
                    option.textContent = `${hour}:00`;
                    timeSelect.appendChild(option);
                }
            } else {
                const option = document.createElement('option');
                option.textContent = 'No available slots on this day';
                timeSelect.appendChild(option);
            }
        });
    </script>
</body>

</html>