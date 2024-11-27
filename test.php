<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

include 'db.php'; // Include the database connection

// Fetch all admins from the database using PDO
$queryAdmins = "SELECT * FROM admins ORDER BY username ASC";
$stmtAdmins = $pdo->prepare($queryAdmins);
$stmtAdmins->execute();
$admins = $stmtAdmins->fetchAll(PDO::FETCH_ASSOC);

// Fetch all users from the database using PDO
$queryUsers = "SELECT * FROM users ORDER BY username ASC";
$stmtUsers = $pdo->prepare($queryUsers);
$stmtUsers->execute();
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List - Admin Dashboard</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
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
            margin-bottom: 30px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .user-table th,
        .user-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .user-table th {
            background-color: #f2f2f2;
        }

        .user-table td {
            background-color: #fff;
        }

        .user-table td a {
            color: #2575fc;
            text-decoration: none;
        }

        .user-table td a:hover {
            text-decoration: underline;
        }

        .button {
            background-color: #2575fc;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-align: center;
            display: inline-block;
        }

        .button:hover {
            background-color: #6a11cb;
        }

        .success-message,
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        .success-message {
            color: green;
        }
        .button {
            background-color: #6a11cb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-align: center;
            display: inline-block;
        }

        .button:hover {
            background-color: #6a11cb;
        }
        .button a{
            color: #f4f4f4;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>User List</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success-message"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Display Admins -->
        <h3>Admins</h3>
        <div class="button">
<a href='addadmin.php'>Add Admin</a>
        </div>
        <table class="user-table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($admins as $admin) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>{$admin['username']}</td>
                        <td>{$admin['role']}</td>
                        <td><a href='admindelete.php?id={$admin['admin_id']}'>Delete</a></td>
                    </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>

        <!-- Display Users -->
        <h3>Users</h3>
        <div class="button">
<a href='adduser.php'>Add User</a>
        </div>
        <table class="user-table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($users as $user) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>{$user['username']}</td>
                        <td>{$user['user_id']}</td>
                        <td> <a href='userdelete.php?id={$user['user_id']}'>Delete</a> </td>
                    </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
        <div>
            <a href="admindashboard.php" class="button">Back to Dashboard</a>
        </div>

    </div>
    

</body>

</html>
