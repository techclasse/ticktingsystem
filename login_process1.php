<?php
session_start(); // Start session to track logged-in admin

// Include the database connection file
include 'db.php'; // Make sure to include db.php to use the $pdo variable

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Sanitize and get the user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $rememberMe = isset($_POST['rememberMe']) ? true : false;

    // Validate input (simple check)
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: login1.php"); // Redirect back to the login page
        exit();
    }

    try {
        // Prepare SQL query to fetch admin user from the database
        $query = "SELECT * FROM admins WHERE username = :username"; // Replace 'admins' with your actual admin table name
        $stmt = $pdo->prepare($query);
        
        // Bind the username parameter
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the admin user from the database
        $admin = $stmt->fetch();

        if ($admin) {
            // Check if the password matches (use password_verify for hashed passwords)
            if ($password === $admin['password']) { // Make sure passwords are properly hashed and compared in real-world use
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'admin'; // Optionally store the role if needed

                // If "Remember Me" is checked, set a cookie (valid for 30 days)
                if ($rememberMe) {
                    setcookie('username', $username, time() + (30 * 24 * 60 * 60), "/"); // 30 days
                }

                // Redirect to the admin dashboard after successful login
                header("Location: admindashboard.php");
                exit();
            } else {
                // Invalid password
                $_SESSION['error'] = "Invalid username or password.";
                header("Location: login1.php");
                exit();
            }
        } else {
            // Admin does not exist
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login1.php");
            exit();
        }

    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login1.php");
        exit();
    }

} else {
    // If the form wasn't submitted, redirect to the login page
    header("Location: login1.php");
    exit();
}
