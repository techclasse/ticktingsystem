<?php
// db.php: Database connection
$servername = "localhost"; // or your host
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "ticket"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
