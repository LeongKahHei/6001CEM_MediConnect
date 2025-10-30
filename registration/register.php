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
                    <button class="btn" id="loginBtn" onclick="login.php">Log In</button></a>
                <a href="register.php">
                    <button class="btn white-btn" id="registerBtn" onclick="login.php">Sign Up</button></a>
            </div>
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>

        <div class="form-box">
            <!---registration form ------>
            <div class="login-container" id="login">
                <div class="top">
                    <header>Patient sign up</header>
                </div>
                <form method="post" action="register.php">
                    <?php include('errors.php'); ?>
                    <div class="two-forms">
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="Firstname" name="fname" required>
                            <p><br></p>
                        </div>
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="Lastname" name="lname" required>
                            <p><br></p>
                        </div>
                    </div>
                    <div class="input-box">
                        <input type="email" class="input-field" placeholder="Email" name="email"
                            title="Please enter a valid email address (e.g., name@example.com)" required>
                        <p><br></p>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" placeholder="Password" name="password" id="password"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                            required>
                        <label>
                            <input type="checkbox" id="togglePassword"> Show Password
                        </label>
                        <p><br></p>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" placeholder="Confirm Password"
                            name="confirm_password" id="confirm_password" required>
                        <p id="password-error" style="color:red; font-size:14px;"></p>
                        <label>
                            <input type="checkbox" id="toggleConfirmPassword"> Show Password
                        </label>
                        <p><br></p>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" value="Register" name="reg_user">
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
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