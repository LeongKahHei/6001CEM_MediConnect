<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (!isset($_SESSION['id'])) {
    die("unauthorized");
}

if (isset($_POST['booking_id']) && isset($_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    $doctor_id = $_SESSION['id'];

    $allowed = ['accepted', 'rejected', 'cancelled'];
    if (!in_array($status, $allowed)) {
        die("invalid");
    }

    $sql = "UPDATE booking SET status = ? WHERE id = ? AND doctor_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sii", $status, $booking_id, $doctor_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "missing";
}
?>
