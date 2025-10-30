<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <nav class="nav" id="navbar">
        <div class="nav-logo">
            <p>MediConnect</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="admin.php" class="link active">All accounts</a></li>
                <li><a href="bookings.php" class="link">All bookings</a></li>
                <li><a href="regisDoc.php" class="link">Doctor register</a></li>
            </ul>
        </div>
        <a href="../registration/login.php">
            <button class="nav-btn" id="logoutBtn">Log Out</button>
        </a>
    </nav>

    <header>
        <div class="container">
            <h1>All accounts</h1>
        </div>
    </header>

    <?php
    $db = mysqli_connect('localhost', 'root', '', 'mediconnect');
    $query_doctor = "SELECT id, username, email FROM users_doctor";
    $query_patient = "SELECT id, username, email FROM users_patient";
    $result_doctor = mysqli_query($db, $query_doctor);
    $result_patient = mysqli_query($db, $query_patient);

    $doctors = [];
    $patients = [];
    if ($result_doctor->num_rows > 0) {
        while ($user = $result_doctor->fetch_assoc()) {
            $doctors[] = $user;
        }
    }
    if ($result_patient->num_rows > 0) {
        while ($user = $result_patient->fetch_assoc()) {
            $patients[] = $user;
        }
    }
    ?>

    <div class="container1">
        <section id="admin-controls" class="container">

            <!-- Manage doctors Section -->
            <div class="account-management">
                <h3>Doctor Accounts</h3>
                <?php
                if (!empty($doctors)) {
                    echo "<table>";
                    echo "<thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>";
                    echo "<tbody>";
                    foreach ($doctors as $doctor) {
                        echo "<tr>";
                        echo "<td>" . $doctor['id'] . "</td>";
                        echo "<td>" . $doctor['username'] . "</td>";
                        echo "<td>" . $doctor['email'] . "</td>";
                        echo "<td><a href='deleteDoctor.php?id=" . $doctor['id'] . "' class='btn delete'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No doctor accounts found.</p>";
                }
                ?>
            </div>

            <!-- Manage patients Section -->
            <div class="account-management">
                <h3>Patient Accounts</h3>
                <?php
                if (!empty($patients)) {
                    echo "<table>";
                    echo "<thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>";
                    echo "<tbody>";
                    foreach ($patients as $patient) {
                        echo "<tr>";
                        echo "<td>" . $patient['id'] . "</td>";
                        echo "<td>" . $patient['username'] . "</td>";
                        echo "<td>" . $patient['email'] . "</td>";
                        echo "<td><a href='deletePatient.php?id=" . $patient['id'] . "' class='btn delete'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No patient accounts found.</p>";
                }
                ?>
            </div>
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