<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login1.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Include database connection
include 'db.php';

// Handle form submission to add new admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate the form fields
    if (empty($username) || empty($password) || empty($role)) {
        $error_message = "All fields are required.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new admin into the database
        $query = "INSERT INTO admins (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $pdo->prepare($query);
        
        // Execute the statement
        if ($stmt->execute(['username' => $username, 'password' => $hashed_password, 'role' => $role])) {
            $success_message = "New admin created successfully!";
        } else {
            $error_message = "Failed to create new admin.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Admin - Ticket Management System</title>
    <style>
        /* Add your styling here */
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
        input[type="text"], input[type="password"], select {
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
        <h2>Create New Admin</h2>

        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success-message'>$success_message</p>";
        }
        ?>

        <form action="addadmin.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="button">Create Admin</button>
            </div>
        </form>

        <!-- Back to Dashboard Button -->
        <div>
            <a href="test.php" class="button">Back to Userlist</a>
        </div>

    </div>

</body>
</html>
