<?php
session_start(); // Start session to track logged-in user

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

include 'db.php'; // Include database connection (PDO)

// Check if ticket_id is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Fetch ticket details from the database using PDO
    $query = "SELECT * FROM tickets WHERE ticket_id = :ticket_id AND created_by = :created_by"; // Ensure the ticket belongs to the logged-in user
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT); // Bind ticket_id parameter
    $stmt->bindParam(':created_by', $_SESSION['username'], PDO::PARAM_STR); // Bind the user to ensure they only view their own tickets
    $stmt->execute();

    $ticket = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch ticket details

    if ($ticket) {
        // Ticket found, display details
    } else {
        $_SESSION['error'] = "Ticket not found or you do not have access to this ticket.";
        header("Location: tickets.php"); // Redirect back to the tickets list page
        exit();
    }

    // Fetch existing replies for the ticket
    $repliesQuery = "SELECT * FROM ticket_replies WHERE ticket_id = :ticket_id ORDER BY created_at DESC";
    $stmt = $pdo->prepare($repliesQuery);
    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
    $stmt->execute();

    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all replies
} else {
    $_SESSION['error'] = "Invalid ticket ID.";
    header("Location: tickets.php"); // Redirect back to the tickets page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ticket - Helpdesk System</title>
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

        .ticket-details,
        .ticket-reply {
            margin-bottom: 30px;
        }

        .ticket-details p {
            font-size: 16px;
        }

        .ticket-details .label {
            font-weight: bold;
        }

        .reply-section {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .reply-section h3 {
            text-align: center;
        }

        .reply-box {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .reply-button {
            background-color: #2575fc;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .reply-button:hover {
            background-color: #6a11cb;
        }

        .replies {
            margin-top: 20px;
        }

        .replies .reply {
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .replies .reply .user {
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .button {
            background-color: #6a11cb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            background-color: #6a11cb;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Ticket Details</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success-message"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if ($ticket): ?>
            <div class="ticket-details">
                <p><span class="label">Ticket ID:</span> <?= $ticket['ticket_id']; ?></p>
                <p><span class="label">Subject:</span> <?= $ticket['subject']; ?></p>
                <p><span class="label">Department:</span> <?= $ticket['department']; ?></p>
                <p><span class="label">Created By:</span> <?= $ticket['created_by']; ?></p>
                <p><span class="label">Created At:</span> <?= $ticket['created_at']; ?></p>
                <p><span class="label">Description:</span> <?= nl2br($ticket['description']); ?></p>
                <p><span class="label">Status:</span> <?= $ticket['status']; ?></p>
            </div>

            <!-- Display ticket replies -->
            <div class="replies">
                <h3>Replies</h3>
                <?php if (empty($replies)) : ?>
                    <p>No replies yet.</p>
                <?php else : ?>
                    <?php foreach ($replies as $reply) : ?>
                        <div class="reply">
                            <p class="user"><?= $reply['user']; ?> <span style="font-weight: normal; font-size: 12px;">(<?= $reply['created_at']; ?>)</span></p>
                            <p><?= nl2br($reply['reply']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


        <?php else: ?>
            <div class="error-message">Ticket not found or you do not have access to this ticket.</div>
        <?php endif; ?>

        <!-- Button to go back to the tickets list -->
        <a href="ticket.php" class="button">Back to Tickets</a>
    </div>

</body>

</html>
