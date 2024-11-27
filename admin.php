<?php
session_start(); // Start session to track logged-in user

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login1.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

include 'db.php'; // Include database connection

// Fetch all tickets from the database
$query = "SELECT * FROM tickets ORDER BY created_at DESC";
$result = $conn->query($query);

// Handle ticket update (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateTicket'])) {
    $ticket_id = $_POST['ticket_id'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'];

    if (!empty($ticket_id) && !empty($status)) {
        // Update ticket in the database
        $updateQuery = "UPDATE tickets SET status = ?, assigned_to = ? WHERE ticket_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $status, $assigned_to, $ticket_id);
        $stmt->execute();

        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: admin.php"); // Redirect to the admin page to show updated tickets
        exit();
    } else {
        $_SESSION['error'] = "Status and assigned user are required.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ticket Management</title>
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

        .ticket-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ticket-table th,
        .ticket-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .ticket-table th {
            background-color: #f2f2f2;
        }

        .ticket-table td {
            background-color: #fff;
        }

        .ticket-table td a {
            color: #2575fc;
            text-decoration: none;
        }

        .ticket-table td a:hover {
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

        .form-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container input,
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container button {
            background-color: #2575fc;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #6a11cb;
        }

        .error-message,
        .success-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        .success-message {
            color: green;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Manage All Tickets - Admin Panel</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success-message"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Display tickets -->
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($ticket = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$counter}</td>
                        <td>{$ticket['ticket_id']}</td>
                        <td>{$ticket['subject']}</td>
                        <td>{$ticket['department']}</td>
                        <td>{$ticket['status']}</td>
                        <td>{$ticket['assigned_to']}</td>
                        <td>
                            <a href='view_ticket.php?id={$ticket['ticket_id']}'>View</a> | 
                            <a href='edit.php?id={$ticket['ticket_id']}'>Edit</a>
                        </td>
                    </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>

        <!-- Update Ticket Form -->
        <div class="form-container">
            <h3>Update Ticket</h3>
            <form action="admin1.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id']; ?>">
                <select name="status" required>
                    <option value="Open" <?= $ticket['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                    <option value="Closed" <?= $ticket['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                    <option value="Pending" <?= $ticket['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                </select>

                <select name="assigned_to" required>
                    <option value="">Select User</option>
                    <?php
                    // Fetch users from the database to assign tickets
                    $userQuery = "SELECT username FROM users"; // Assuming 'users' table has 'username' column
                    $userResult = $conn->query($userQuery);
                    while ($user = $userResult->fetch_assoc()) {
                        echo "<option value='{$user['username']}'>{$user['username']}</option>";
                    }
                    ?>
                </select>

                <button type="submit" name="updateTicket">Update Ticket</button>
            </form>
        </div>

    </div>

</body>

</html>
