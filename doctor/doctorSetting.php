<?php
//Database connection
session_start();
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (!isset($_SESSION['id'])) {
    die("Access denied. Please log in.");
}

$id = $_SESSION['id'];

// Fetch current doctor info
$query = "SELECT username, profile_picture, specialty, experience, phone, languages FROM users_doctor WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

// Fetch distinct specialties for dropdown
$specialty_query = "SHOW COLUMNS FROM users_doctor LIKE 'specialty'";
$specialty_result = $db->query($specialty_query);
$specialty_row = $specialty_result->fetch_assoc();

$specialty_type = $specialty_row['Type']; // if in coclumn specialty enum('cardiology','urology'++)
preg_match("/^enum\('(.*)'\)$/", $specialty_type, $matches);
$specialties = explode("','", $matches[1]);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $specialty = $_POST['specialty'];
    $experience = $_POST['experience'];
    $phone = $_POST['phone'];
    $languages = $_POST['languages'];

    // Handle profile picture upload
    $profile_picture = $doctor['profile_picture']; // keep old picture if not updated
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../uploads/doctor_profiles/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file type
        if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            }
        }
    }

    // Update database
    $update_query = "UPDATE users_doctor 
                     SET username=?, profile_picture=?, specialty=?, experience=?, phone=?, languages=?
                     WHERE id=?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("ssssssi", $username, $profile_picture, $specialty, $experience, $phone, $languages, $id);
    $stmt->execute();

    echo "<script>alert('Profile updated successfully!'); window.location.href='doctorSetting.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="doctorSetting.css">
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="doctorTime.php" class="link">Timeslot</a></li>
                <li><a href="doctorBooking.php" class="link">Bookings</a></li>
                <li><a href="doctorSetting.php" class="link active">Profile Setting</a></li>
            </ul>
        </div>
        <a href="../logout/logout.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>

    <header>
        <div class="container">
            <h1>Edit Your Profile</h1>
            <p>Update the information displayed on your profile</p>
        </div>
    </header>

    <section id="edit-profile" class="container">

        <form action="" method="POST" enctype="multipart/form-data">
            <?php if (!empty($doctor['profile_picture'])): ?>
                <img src="<?php echo htmlspecialchars($doctor['profile_picture']); ?>" alt="Profile Picture">
            <?php endif; ?>

            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($doctor['username']); ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Specialty:</label>
                <select name="specialty" required>
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $specialty): ?>
                    <option value="<?php echo htmlspecialchars($specialty); ?>" 
                    <?php if ($doctor['specialty'] == $specialty) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($specialty); ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Experience:</label>
                <input type="text" name="experience" value="<?php echo htmlspecialchars($doctor['experience']); ?>">
            </div>

            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($doctor['phone']); ?>"
                    pattern="[0-9]+" title="Please enter numbers only" required>
            </div>

            <div class="form-group">
                <label>Languages:</label>
                <input type="text" name="languages" value="<?php echo htmlspecialchars($doctor['languages']); ?>">
            </div>
            <!-- Profile Picture Section -->
            <div class="form-group">
                <label>Profile Picture:</label>
                <input type="file" name="profile_picture" accept="image/*">
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn" name="update">Save Changes</button>
            </div>
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


    </script>

</body>

</html>