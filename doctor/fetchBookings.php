<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (!isset($_SESSION['id'])) {
    die("unauthorized");
}

$doctor_id = $_SESSION['id'];
$type = $_GET['type'] ?? 'pending';

// Validate input
$allowed = ['pending', 'accepted'];
if (!in_array($type, $allowed)) {
    die("invalid type");
}

$sql = "SELECT booking.id, users_patient.username, booking.booking_date, booking.booking_time, booking.status
        FROM booking
        JOIN users_patient ON booking.patient_id = users_patient.id
        WHERE booking.doctor_id = ? AND booking.status = ?
        ORDER BY booking.booking_date ASC";
$stmt = $db->prepare($sql);
$stmt->bind_param("is", $doctor_id, $type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<thead><tr>
            <th>Patient Name</th>
            <th>Booking Date</th>
            <th>Booking Time</th>";
    if ($type === 'pending' || $type === 'accepted') {
        echo "<th>Actions</th>";
    }
    echo "</tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr id='booking-" . $row['id'] . "'>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['booking_time']) . "</td>";

        if ($type === 'pending') {
            echo "<td>
                    <button class='accept-btn' onclick=\"updateBookingStatus(" . $row['id'] . ", 'accepted')\">Accept</button> |
                    <button class='reject-btn' onclick=\"updateBookingStatus(" . $row['id'] . ", 'rejected')\">Reject</button>
                  </td>";
        } elseif ($type === 'accepted') {
            echo "<td>
                <button class='cancel-btn' onclick=\"updateBookingStatus(" . $row['id'] . ", 'cancelled')\">Cancel</button>
                </td>";
        }

        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    if ($type === 'pending') {
    echo "<p style='text-align: center'>No pending bookings found.</p>";
    }
    if ($type === 'accepted') {
    echo "<p style='text-align: center'>No accepted bookings found.</p>";
    }
}
?>