<?php
// Include the database connection file
require_once 'db1.php';  // Adjust the path as needed for your project

// Query to fetch only open tickets
$query = "SELECT * FROM tickets WHERE status = 'Open' ORDER BY created_at DESC";
$result = $conn->query($query);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Tickets - Helpdesk System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
    
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
        margin-bottom: 20px;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #6a11cb;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #6a11cb;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            background-color: #6a11cb;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            margin-bottom: -20px;
        }

        .btn:hover {
            background-color: #6a11cb;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Open Tickets</h1>

    <!-- The open tickets table will be displayed here -->
    <?php
    // Insert the PHP script here to fetch and display the open tickets (above code)
    // Check if there are open tickets
if ($result->num_rows > 0) {
    // Start building the HTML table to display open tickets
    echo "<table border='1' cellpadding='10' cellspacing='0'>
            <tr>
                <th>#</th>
                <th>Ticket ID</th>
                <th>Subject</th>
                <th>Department</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";

    // Counter for displaying row numbers
    $counter = 1;

    // Loop through the result set and display each open ticket
    while ($ticket = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$counter}</td>
                <td>{$ticket['ticket_id']}</td>
                <td>{$ticket['subject']}</td>
                <td>{$ticket['department']}</td>
                <td>{$ticket['created_at']}</td>
                <td>{$ticket['status']}</td>
                <td>
                    <a href='viewticket.php?id={$ticket['ticket_id']}'>View</a> | 
                    <a href='close_ticket.php?id={$ticket['ticket_id']}'>Close</a>
                </td>
              </tr>";
        $counter++;
    }

    // Close the table
    echo "</table>";
} else {
    // If no open tickets are found, display a message
    echo "<p>No open tickets found.</p>";
}
    ?>

    <!-- Button to go back to the tickets list -->
    <a href="ticket.php" class="btn">Back to Tickets List</a>
</div>

</body>
</html>
