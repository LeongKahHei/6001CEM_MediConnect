<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');
$doctor_id = $_SESSION['id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['availability'])) {
    $availability = $_POST['availability'];

    // Remove old availability
    $db->query("DELETE FROM doctor_availability WHERE doctor_id = $doctor_id");

    // Insert new availability
    $stmt = $db->prepare("INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
    foreach ($availability as $day => $times) {
        if (isset($times['available']) && !empty($times['start']) && !empty($times['end'])) {
            $stmt->bind_param("isss", $doctor_id, $day, $times['start'], $times['end']);
            $stmt->execute();
        }
    }
    $stmt->close();
    echo "<script>alert('Availability updated successfully!'); window.location.href='doctorTime.php';</script>";
}
?>
