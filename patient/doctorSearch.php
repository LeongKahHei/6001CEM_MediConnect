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

// Fetch distinct specialties from doctor table (for sidebar)
$sql_specialties = "SELECT DISTINCT specialty FROM users_doctor ORDER BY specialty ASC";
$result_specialties = $db->query($sql_specialties);

$specialties = [];
if ($result_specialties && $result_specialties->num_rows > 0) {
    while ($row = $result_specialties->fetch_assoc()) {
        $specialties[] = $row['specialty'];
    }
}

$current_specialty = isset($_GET['specialty']) ? $_GET['specialty'] : 'all';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Listings</title>
    <link rel="stylesheet" href="doctorSearch.css">
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="homepage.php" class="link">Home</a></li>
                <li><a href="doctorSearch.php" class="link active">Find doctor</a></li>
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
            <h1>All doctors</h1>
            <p>Find all available doctors here</p>
        </div>
    </header>

    <div class="container1">
        <aside class="sidebar">
            <h2>Filter</h2>
            <ul class="specialty-filter">
                <!-- All doctors -->
                <li>
                    <a href="?specialty=all" class="<?php echo ($current_specialty === 'all') ? 'active' : ''; ?>">
                        All
                    </a>
                </li>

                <!-- Specialty options -->
                <?php foreach ($specialties as $specialty): ?>
                    <li>
                        <a href="?specialty=<?php echo urlencode($specialty); ?>"
                            class="<?php echo ($current_specialty === $specialty) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($specialty); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <section id="doctor-list" class="container">
            <?php
            $specialty = isset($_GET['specialty']) ? $_GET['specialty'] : 'all';

            if ($specialty === 'all' || empty($specialty)) {
                // Show all doctors
                $sql_doctors = "SELECT * FROM users_doctor";
                $result_doctors = $db->query($sql_doctors);
            } else {
                // Show only doctors with the selected specialty
                $stmt = $db->prepare("SELECT * FROM users_doctor WHERE specialty = ?");
                $stmt->bind_param("s", $specialty);
                $stmt->execute();
                $result_doctors = $stmt->get_result();
            }

            // Output data for each doctor
            if ($result_doctors && $result_doctors->num_rows > 0) {
                while ($row = $result_doctors->fetch_assoc()) {
                    echo "<div class='doctor-card'>";
                    $profilePic = !empty($row['profile_picture']) ? $row['profile_picture'] : '../images/profile.png';
                    echo "<img src='" . htmlspecialchars($profilePic) . "' alt='Doctor Photo'>";
                    echo "<div class='doctor-info'>";
                    echo "<h2>" . htmlspecialchars($row['username']) . "</h2>";
                    echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
                    echo "<p>Specialty: " . htmlspecialchars($row['specialty']) . "</p>";
                    echo "<a href='doctorProfile.php?doctor_id=" . $row['id'] . "' class='view-profile-btn'>View Profile</a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No doctors available for this specialty.</p>";
            }
            ?>
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