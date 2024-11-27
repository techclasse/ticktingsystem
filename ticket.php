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
$query = "SELECT * FROM tickets WHERE created_by = :username ORDER BY created_at DESC";
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
            background-color: navy;
            color: white;
        }

        .ticket-table td {
            background-color: #fff;
        }

        .ticket-table td a {
            color: ;
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
        .form-container input,
        .form-container select,
        {
            background-color: navy;

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
        h1{
        color: navy;
        }
        .option{
            background-color: ;
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
a{

}


    </style>
</head>

<body>

    <div class="container">
        <h2>Manage Your Tickets</h2>
         <!-- Logout Button -->
     <div class="logout-button">
            <a href="login.php" class="button">Logout</a>
        </div>

        <!-- Display the logged-in user's username -->
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
        echo "<tr>
            <td>{$counter}</td>
            <td>{$ticket['ticket_id']}</td>
            <td>{$ticket['subject']}</td>
            <td>{$ticket['department']}</td>
            <td>{$ticket['created_at']}</td>
            <td>{$ticket['status']}</td>
            <td>
                <a href='userview.php?id={$ticket['ticket_id']}' class='btn view-btn'>View Ticket</a> | 
                <a href='edit.php?id={$ticket['ticket_id']}' class='btn edit-btn'>Edit</a> | 
                <a href='close.php?id={$ticket['ticket_id']}' class='btn close-btn'>Close</a>
            </td>
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
                <select  name="department" required>
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

</body>

</html>