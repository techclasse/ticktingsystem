<?php
session_start();

// Check if the admin is logged in (only allow admin role to add a user)
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login1.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Include database connection
include 'db.php';

// Handle form submission to add new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    // Add role selection for users

    // Validate the form fields
    if (empty($username) || empty($password) || empty($email)) {
        $error_message = "All fields are required.";
    } else {
        // Check if username already exists in the database
        $checkUsernameQuery = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $pdo->prepare($checkUsernameQuery);
        $stmt->execute(['username' => $username]);
        $usernameExists = $stmt->fetchColumn();

        if ($usernameExists) {
            $error_message = "Username already exists.";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
            $stmt = $pdo->prepare($query);
            
            // Execute the statement
            if ($stmt->execute(['username' => $username, 'password' => $hashed_password, 'email' => $email])) {
                $success_message = "New user created successfully!";
            } else {
                $error_message = "Failed to create new user.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User - Ticket Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .button {
            background-color: #6a11cb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #5b0c9c;
        }
        .error-message, .success-message {
            color: red;
            text-align: center;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Create New User</h2>

        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success-message'>$success_message</p>";
        }
        ?>

        <form action="adduser.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="button">Create User</button>
            </div>
        </form>

        <!-- Back to Dashboard Button -->
        <div>
            <a href="index.php" class="button">Back to Dashboard</a>
        </div>

    </div>

</body>
</html>
