<?php include('db.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registration.css">
    <title>Login / Registration</title>
</head>

<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>MediConnect</p>
            </div>

            <div class="nav-button">
                <a href="login.php">
                    <button class="btn white-btn" id="loginBtn" onclick="login.php">Log In</button></a>
                <a href="register.php">
                    <button class="btn" id="registerBtn" onclick="login.php">Sign Up</button></a>
            </div>
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>

        <div class="form-box">
            <!-- login form --->
            <div class="login-container" id="login">
                <div class="top">
                    <header>Login</header>
                </div>
                <form method="post" action="login.php">
                    <?php include('errors.php'); ?>
                    <div class="input-box">
                        <input type="text" class="input-field" placeholder="Email" name="email" required>
                        <p><br></p>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" placeholder="Password" name="password" id="password"
                            required>
                        <label>
                            <input type="checkbox" id="togglePassword"> Show Password
                        </label>
                        <p><br></p>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" value="Sign In" name="login_user">
                    </div>

                </form>
            </div>
        </div>

        <script>
        // Show/hide password
        document.getElementById("togglePassword").addEventListener("change", function () {
            const password = document.getElementById("password");
            password.type = this.checked ? "text" : "password";
        });
        </script>

</body>

</html>