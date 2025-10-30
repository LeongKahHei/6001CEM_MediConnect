<?php
// Database connection
$db = mysqli_connect('localhost', 'root', '', 'mediconnect');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get the user ID 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the user from the table
    $sql = "DELETE FROM users_patient WHERE id=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Account deleted successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}

$db->close();
?>
