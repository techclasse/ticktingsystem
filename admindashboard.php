<?php
session_start(); // Start session to track logged-in admin

// Check if the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login1.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

// Get admin information
$username = $_SESSION['username'];

// Include database connection
include 'db.php';

// Fetch ticket data
$query = "SELECT COUNT(*) AS total_tickets, SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) AS open_tickets FROM tickets";
$stmt = $pdo->prepare($query);
$stmt->execute();
$ticketData = $stmt->fetch();

// Fetch user data
$query = "SELECT COUNT(*) AS total_users FROM users";
$stmt = $pdo->prepare($query);
$stmt->execute();
$userData = $stmt->fetch();

// Fetch latest tickets
$latestTicketsQuery = "SELECT ticket_id, subject, department, status, created_at FROM tickets ORDER BY created_at DESC LIMIT 5";
$stmt = $pdo->prepare($latestTicketsQuery);
$stmt->execute();
$latestTickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ticket Management System</title>
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
            margin-bottom: 30px;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            margin-top: 20px;
            gap: 30px;
            
        }

        .stats div {
            background-color: navy;
            color: white;
            padding: 20px;
            border-radius: 8px;
            width: 30%;
            text-align: center;
            height: 100%;
        }

        .stats div h3 {
            margin: 0;
        }
.container .stats div a{
    text-decoration: none;
    color: #fff;
    
}
        .ticket-table, .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ticket-table th, .ticket-table td,
        .user-table th, .user-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .ticket-table th, .user-table th {
            background-color: navy;
            color: #f4f4f4;
        }

        .ticket-table td a, .user-table td a {
            color: ;
            text-decoration: none;
        }

        .ticket-table td a:hover, .user-table td a:hover {
            text-decoration: underline;
        }

        .button {
            background-color: #6a11cb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-align: end;
            display: inline-block;
        }

        .button:hover {
            background-color: #6a11cb;
        }

        .logout-button {
            text-align: end;
            margin-top: -50px;
        
        
        }
        .logout-button a {
        text-decoration: none;
        
        
        }

        .test {
            background-color: #6a11cb;
            color: white;
            padding: 20px;
            border-radius: 8px;
            width: 97%;
            text-align: center;
            height: 5%;
        }

        .test a {
            color: white;
            text-decoration: none;
        }
     .stats    a{
    
        }


                /* General button styles */
a.btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* View button style */
a.view-btn {
    background-color: #6a11cb/* Green */
}

a.view-btn:hover {
    background-color: #6a11cb;
    transform: scale(1.05);
}

/* Edit button style */
a.edit-btn {
    background-color: #ffa500; /* Orange */
}

a.edit-btn:hover {
    background-color: #e69500;
    transform: scale(1.05);
}

/* Close button style */
a.close-btn {
    background-color: #f44336; /* Red */
}

a.close-btn:hover {
    background-color: #e53935;
    transform: scale(1.05);
}

/* Optional: Add some margin between the buttons */
td a {
    margin-right: 2px;
    gap: ;
    
}
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, Admin</h2>
        
     <!-- Logout Button -->
     <div class="logout-button">
            <a href="login1.php" class="button">Logout</a>
        </div>
        
        <!-- Display Admin Stats -->
        <div class="stats">
            
            <div >
                <a href="totalticket.php">
                <h3>Total Tickets</h3>
                <p><?= $ticketData['total_tickets'] ?></p></a>
            </div>
            <div>
                <a href="open.php">
                <h3>Open Tickets</h3>
                <p><?= $ticketData['open_tickets'] ?></p></a>
            </div>
              <!-- Total Users Block -->
        <div class="test1">
            <a href="test.php">
                <h3>Total Users</h3>
                <p><?= $userData['total_users'] ?> </p>
            </a>
        </div>

        </div>

      
        <!-- Latest Tickets -->
        <h3>Latest Tickets</h3>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($latestTickets as $ticket) : ?>
                    <tr>
                        <td><?= $ticket['ticket_id'] ?></td>
                        <td><?= $ticket['subject'] ?></td>
                        <td><?= $ticket['department'] ?></td>
                        <td><?= $ticket['status'] ?></td>
                        <td><?= $ticket['created_at'] ?></td>
                        <td>
                        <a href='view.php?id={$ticket['ticket_id']}' class='btn view-btn'>View Ticket</a>
                         |  <a href='edit.php?id={$ticket['ticket_id']}' class='btn edit-btn'>Edit</a> | 
                        
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>

</body>
</html>
