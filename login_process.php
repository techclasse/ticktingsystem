<?php
session_start();

// Database connection (adjust as needed)
$servername = "localhost";
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "ticket"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is being sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input
    $user_username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $user_password = isset($_POST['password']) ? $_POST['password'] : null;

    // Query to find the user in the 'users' table
    $sql_user = "SELECT * FROM users WHERE username = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param('s', $user_username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    // Query to find the user in the 'admins' table
    $sql_admin = "SELECT * FROM admins WHERE username = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param('s', $user_username);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    // Check if user is found in 'users' table
    if ($result_user->num_rows > 0) {
        // User found in 'users' table
        $user = $result_user->fetch_assoc();

        // Verify password
        if (password_verify($user_password, $user['password'])) {
            // Password is correct, set session variables for regular user
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // Redirect to user dashboard
            header("Location: ticket.php");
            exit();
        } else {
            // Incorrect password
            echo "Invalid password for user.";
        }
    } 
    // Check if user is found in 'admins' table
    elseif ($result_admin->num_rows > 0) {
        // User found in 'admins' table
        $admin = $result_admin->fetch_assoc();

        // Verify password
        if (password_verify($user_password, $admin['password'])) {
            // Password is correct, set session variables for admin
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];
            $_SESSION['user_id'] = $admin['id'];

            // Redirect to admin dashboard
            header("Location: admindashboard.php");
            exit();
        } else {
            // Incorrect password
            echo "Invalid password for admin.";
        }
    } else {
        // No user found with this username
        echo "No user found with this username.";
    }
} else {
    echo "Form data not submitted.";
}

// Close the database connection
$conn->close();
?>
