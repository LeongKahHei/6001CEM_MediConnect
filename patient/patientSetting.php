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

if (!isset($_SESSION['id'])) {
    die("Access denied. Please log in.");
}

$id = $_SESSION['id'];

// Fetch current patient info
$query = "SELECT username, phone FROM users_patient WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];

    // Update database
    $update_query = "UPDATE users_patient 
                     SET username=?, phone=?
                     WHERE id=?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("ssi", $username, $phone, $id);
    $stmt->execute();

    echo "<script>alert('Profile updated successfully!'); window.location.href='patientSetting.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient Profile</title>
    <link rel="stylesheet" href="patientSetting.css">

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
                <li><a href="patientSetting.php" class="link active">My profile</a></li>
            </ul>
        </div>
        <a href="../logout/logout.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>


    <header>
        <div class=" container">
            <h1>Edit Your Profile</h1>
            <p>Update the information displayed on your profile</p>
        </div>
    </header>

    <section id="edit-profile" class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($patient['username']); ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>"
                    pattern="[0-9]+" title="Please enter numbers only" required>
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