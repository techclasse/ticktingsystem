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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            background-color: #f2f2f2;
        }

        .ticket-table td a, .user-table td a {
            color: #6a11cb;
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
    </style>
</head>
<body>
    <div class="container">
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
                <?php foreach ($total_tickets as $ticket) : ?>
                    <tr>
                        <td><?= $ticket['ticket_id'] ?></td>
                        <td><?= $ticket['subject'] ?></td>
                        <td><?= $ticket['department'] ?></td>
                        <td><?= $ticket['status'] ?></td>
                        <td><?= $ticket['created_at'] ?></td>
                        <td>
                            <a href="view.php?id=<?= $ticket['ticket_id'] ?>">View</a> | 
                            <a href="edit.php?id=<?= $ticket['ticket_id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>