<?php
session_start(); // Start session to track logged-in user

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include 'db.php'; // Include database connection

// Get user information
$username = $_SESSION['username'];

// Check if ticket_id is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Fetch ticket details from the database using PDO
    $query = "SELECT * FROM tickets WHERE ticket_id = :ticket_id AND created_by = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    // If ticket not found, redirect to ticket page
    if (!$ticket) {
        $_SESSION['error'] = "Ticket not found or you don't have permission to edit it.";
        header("Location: ticket.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid ticket ID.";
    header("Location: ticket.php"); // Redirect back to the tickets page
    exit();
}

// Handle ticket update (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateTicket'])) {
    $subject = trim($_POST['subject']);
    $department = trim($_POST['department']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    if (!empty($subject) && !empty($department) && !empty($description)) {
        // Update the ticket in the database
        $updateQuery = "UPDATE tickets SET subject = :subject, department = :department, description = :description, status = :status WHERE ticket_id = :ticket_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success'] = "Ticket updated successfully.";
        header("Location: ticket.php"); // Redirect to the tickets page
        exit();
    } else {
        $_SESSION['error'] = "All fields are required.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket - Helpdesk System</title>
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
            background-color: #6a11cb;
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
    <h2>Edit Ticket</h2>

    <!-- Display success or error messages -->
    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error-message"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success-message"><?= $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Edit ticket form -->
    <div class="form-container">
        <form action="edit.php?id=<?= $ticket['ticket_id']; ?>" method="POST">
            <input type="text" name="subject" value="<?= htmlspecialchars($ticket['subject']); ?>" placeholder="Subject" required>
            <select name="department" required>
                <option value="Support" <?= ($ticket['department'] == 'Support') ? 'selected' : ''; ?>>Support</option>
                <option value="Technical" <?= ($ticket['department'] == 'Technical') ? 'selected' : ''; ?>>Technical</option>
                <option value="Billing" <?= ($ticket['department'] == 'Billing') ? 'selected' : ''; ?>>Billing</option>
            </select>
            <textarea name="description" rows="4" placeholder="Describe your issue..." required><?= htmlspecialchars($ticket['description']); ?></textarea>
            <select name="status" required>
                <option value="Open" <?= ($ticket['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
                <option value="Closed" <?= ($ticket['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                <option value="Pending" <?= ($ticket['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            </select>
            <button type="submit" name="updateTicket">Update Ticket</button>
        </form>
    </div>
</div>

</body>
</html>
