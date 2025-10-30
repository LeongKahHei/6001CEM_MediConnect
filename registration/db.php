<?php
session_start();

$username = "";
$email = "";
$errors = array();

$db = mysqli_connect('localhost', 'root', '', 'mediconnect');


//receive data
if (isset($_POST['reg_user'])) {//patient
    $fname = mysqli_real_escape_string($db, $_POST['fname']);
    $lname = mysqli_real_escape_string($db, $_POST['lname']);
    $username = $fname . ' ' . $lname;
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    //check if user exists
    $doctor_check = "SELECT * FROM users_doctor WHERE email='$email' LIMIT 1";
    $patient_check = "SELECT * FROM users_patient WHERE email='$email' LIMIT 1";

    $doctor_result = mysqli_query($db, $doctor_check);
    $patient_result = mysqli_query($db, $patient_check);

    if (mysqli_num_rows($doctor_result) > 0 || mysqli_num_rows($patient_result) > 0) {
        echo "<script>alert('user already exists!'); window.location.href='login.php';</script>";
    }

    //register user
    if (count($errors) == 0) {
        $password = md5($password);//encrypt password

        $query = "INSERT INTO users_patient (username, email, password)
                VALUES('$username', '$email', '$password')";
        mysqli_query($db, $query);

        echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
    }
}

if (isset($_POST['reg_doc'])) {//doctor
    $fname = mysqli_real_escape_string($db, $_POST['fname']);
    $lname = mysqli_real_escape_string($db, $_POST['lname']);
    $username = $fname . ' ' . $lname;
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $specialty = mysqli_real_escape_string($db, $_POST['specialty']);

    //check if user exists
    $doctor_check = "SELECT * FROM users_doctor WHERE email='$email' LIMIT 1";
    $patient_check = "SELECT * FROM users_patient WHERE email='$email' LIMIT 1";

    $doctor_result = mysqli_query($db, $doctor_check);
    $patient_result = mysqli_query($db, $patient_check);

    if (mysqli_num_rows($doctor_result) > 0 || mysqli_num_rows($patient_result) > 0) {
        echo "<script>alert('user already exists!'); window.location.href='regisDoc.php';</script>";
    } else {//register user
        $password = md5($password);//encrypt password

        $query = "INSERT INTO users_doctor (username, email, password, specialty)
                 VALUES('$username', '$email', '$password', '$specialty')";
        mysqli_query($db, $query);

        echo "<script>alert('Account created successfully!'); window.location.href='../admin/regisDoc.php';</script>";
    }
}


//login
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (count($errors) == 0) {
        $password = md5($password);
        $query_patient = "SELECT * FROM users_patient WHERE (email='$email') AND password='$password'";
        $query_doctor = "SELECT * FROM users_doctor WHERE (email='$email') AND password='$password'";
        $results_patient = mysqli_query($db, $query_patient);
        $results_doctor = mysqli_query($db, $query_doctor);

        if (mysqli_num_rows($results_patient) == 1) {
            $user = mysqli_fetch_assoc($results_patient);
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['success'] = "You are now logged in";

            $user = $results_patient->fetch_assoc();
            header('location: ../patient/homepage.php');

        } else if (mysqli_num_rows($results_doctor) == 1) {
            $user = mysqli_fetch_assoc($results_doctor);
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['success'] = "You are now logged in";

            $user = $results_doctor->fetch_assoc();
            header('location: ../doctor/doctorBooking.php');
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}
$db->close();
?>