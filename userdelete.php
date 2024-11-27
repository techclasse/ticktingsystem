<?php
session_start();

// Check if the admin is logged in and has the correct role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Include the database connection file
include 'db.php';

// Handle delete request
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Ensure that the user to be deleted exists in the database
    $checkUserQuery = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($checkUserQuery);
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch();

    if ($user) {
        // Make sure the logged-in admin is not trying to delete their own account
        if ($_SESSION['username'] == $user['username']) {
            $error_message = "You cannot delete your own account.";
        } else {
            // Delete the user from the database
            $deleteQuery = "DELETE FROM users WHERE user_id = :user_id";
            $deleteStmt = $pdo->prepare($deleteQuery);
            if ($deleteStmt->execute(['user_id' => $userId])) {
                // Redirect with success message
                header("Location: test.php?success=User deleted successfully.");
                exit();
            } else {
                // If something goes wrong, show an error message
                $error_message = "Failed to delete the user. Please try again later.";
            }
        }
    } else {
        $error_message = "User not found.";
    }
} else {
    $error_message = "No user ID provided.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User - Ticket Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
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
        <h2>Delete User</h2>

        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success-message'>$success_message</p>";
        }
        ?>

        <div>
            <a href="test.php" class="button">Back to User List</a>
        </div>
    </div>

</body>
</html>
