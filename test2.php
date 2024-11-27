<?php
session_start(); // Start session to track logged-in user

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include 'db.php'; // Include database connection

// Get user information (username from session)
$username = $_SESSION['username'];

// Fetch tickets for the logged-in user using PDO
$query = "SELECT ticket_id, subject, department, created_at, status, is_replied FROM tickets WHERE created_by = :username ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle ticket creation (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createTicket'])) {
    $subject = trim($_POST['subject']);
    $department = trim($_POST['department']);
    $description = trim($_POST['description']);

    if (!empty($subject) && !empty($department) && !empty($description)) {
        // Insert new ticket into the database using PDO
        $insertQuery = "INSERT INTO tickets (subject, department, created_by, description, status, created_at) 
                        VALUES (:subject, :department, :created_by, :description, 'Open', NOW())";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->bindParam(':created_by', $username, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "Ticket created successfully.";
        header("Location: ticket.php"); // Redirect to the same page to show new ticket
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
    <title>Ticket Management - Helpdesk System</title>
    <style>
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
            background-color: navy;
            color: white;
        }

        .ticket-table td {
            background-color: #fff;
        }

        .ticket-table td a {
            color: #6a11cb;
            text-decoration: none;
        }

        .ticket-table td a:hover {
            text-decoration: underline;
        }

        /* Highlight style for replied tickets */
        .highlight {
            background-color: #ffeb3b;  /* Yellow background for highlighted tickets */
            font-weight: bold;
            color: #000;
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
            background-color: navy;
            color: white;
        }

        .ticket-table td {
            background-color: #fff;
        }

        .ticket-table td a {
            color: #6a11cb;
            text-decoration: none;
        }

        .ticket-table td a:hover {
            text-decoration: underline;
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

        /* Highlight style for replied tickets */
        .highlight {
            background-color: #ffeb3b;  /* Yellow background for highlighted tickets */
            font-weight: bold;
            color: #000;
        }

        .logout-button {
            text-align: end;
            margin-top: -50px;
        }

        .logout-button a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Manage Your Tickets</h2>
        <h1>Welcome, <strong><?= htmlspecialchars($username); ?></strong>!</h1>
        <h4>View and manage tickets that may have responses from the support team.</h4>

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
                    <th>Created</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($result as $ticket) {
                    echo "<tr data-replied='{$ticket['is_replied']}'>
                        <td>{$counter}</td>
                        <td>{$ticket['ticket_id']}</td>
                        <td>{$ticket['subject']}</td>
                        <td>{$ticket['department']}</td>
                        <td>{$ticket['created_at']}</td>
                        <td>{$ticket['status']}</td>
                        <td><a href='userview.php?id={$ticket['ticket_id']}'>View Ticket</a> | <a href='edit.php?id={$ticket['ticket_id']}'>Edit</a> | <a href='close.php?id={$ticket['ticket_id']}'>Close</a></td>
                    </tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>

        <!-- Create a new ticket -->
        <div class="form-container">
            <h3>Create a New Ticket</h3>
            <form action="ticket.php" method="POST">
                <input type="text" name="subject" placeholder="Subject" required>
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="Support">Support</option>
                    <option value="Technical">Technical</option>
                    <option value="Billing">Billing</option>
                </select>
                <textarea name="description" rows="4" placeholder="Describe your issue..." required></textarea>
                <button type="submit" name="createTicket">Create Ticket</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to highlight the tickets with replies
        document.addEventListener("DOMContentLoaded", function() {
            // Select all rows in the ticket table
            const rows = document.querySelectorAll('.ticket-table tbody tr');

            // Loop through each row
            rows.forEach(function(row) {
                // Check if the data-replied attribute is set to 1 (replied)
                const isReplied = row.getAttribute('data-replied');
                if (isReplied == '1') {
                    row.classList.add('highlight');  // Add the highlight class to the row
                }
            });
        });
    </script>

</body>

</html>
