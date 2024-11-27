<?php
session_start(); // Start the session to track logged-in user

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

include 'db.php'; // Include the database connection

// Get ticket ID from URL query parameter
if (isset($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Prepare the query to update the status of the ticket to 'Closed'
    $query = "UPDATE tickets SET status = 'Closed' WHERE ticket_id = :ticket_id AND created_by = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);

    // Execute the query
    if ($stmt->execute()) {
        // Set success message in session
        $_SESSION['success'] = "Ticket #$ticket_id has been closed successfully.";
    } else {
        // Set error message in session
        $_SESSION['error'] = "Failed to close the ticket. Please try again.";
    }
} else {
    // Set error message in session if ticket_id is missing
    $_SESSION['error'] = "Ticket ID is missing.";
}

// Redirect back to the ticket management page
header("Location: ticket.php");
exit();
?>
