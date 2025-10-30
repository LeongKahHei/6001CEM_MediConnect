<?php include('../registration/db.php') ?>
<?php
//Database connection
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch specialties for dropdown
$specialty_query = "SHOW COLUMNS FROM users_doctor LIKE 'specialty'";
$specialty_result = $db->query($specialty_query);
$specialty_row = $specialty_result->fetch_assoc();

$specialty_type = $specialty_row['Type']; // if in coclumn specialty enum('cardiology','urology'++)
preg_match("/^enum\('(.*)'\)$/", $specialty_type, $matches);
$specialties = explode("','", $matches[1]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="regisDoc.css">
    <title>Login / Registration</title>
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="admin.php" class="link">All accounts</a></li>
                <li><a href="bookings.php" class="link">All bookings</a></li>
                <li><a href="regisDoc.php" class="link active">Doctor register</a></li>
            </ul>
        </div>
        <a href="../registration/login.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>

    <header>
        <div class="container">
            <h1>Create doctor accounts</h1>
        </div>
    </header>

    <section id="edit-profile" class="container">

        <form action="" method="post">
            <div class="two-forms">
                <div class="form-group">
                    <label>First name:</label>
                    <input type="text" class="input-field" name="fname" required>
                </div>
                <div class="form-group">
                    <label>last name:</label>
                    <input type="text" class="input-field" name="lname" required>
                </div>
            </div>
            <div class="form-group">
                <label>Specialty:</label>
                <select name="specialty" required>
                    <option value="">-- Select Specialty --</option>
                    <?php foreach ($specialties as $specialty): ?>
                        <option value="<?php echo htmlspecialchars($specialty); ?>">
                            <?php echo htmlspecialchars($specialty); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="input-field" name="email"
                    title="Please enter a valid email address (e.g., name@example.com)" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="input-field" name="password" id="password"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    required>
                <label>
                    <input type="checkbox" id="togglePassword"> Show Password
                </label>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" class="input-field" name="confirm_password" id="confirm_password" required>
                <p id="password-error" style="color:red; font-size:14px;"></p>
                <label>
                    <input type="checkbox" id="toggleConfirmPassword"> Show Password
                </label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Create doctor account" name="reg_doc">
            </div>

        </form>
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

        // Show/hide password
        document.getElementById("togglePassword").addEventListener("change", function () {
            const password = document.getElementById("password");
            password.type = this.checked ? "text" : "password";
        });

        document.getElementById("toggleConfirmPassword").addEventListener("change", function () {
            const confirmPassword = document.getElementById("confirm_password");
            confirmPassword.type = this.checked ? "text" : "password";
        });

        // confirm password validation
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirm_password");
        const errorText = document.getElementById("password-error");

        confirmPassword.addEventListener("input", function () {
            if (confirmPassword.value !== password.value) {
                errorText.textContent = "Passwords do not match";
                confirmPassword.setCustomValidity("Passwords do not match");
            } else {
                errorText.textContent = "";
                confirmPassword.setCustomValidity("");
            }
        });
    </script>

</body>

</html>