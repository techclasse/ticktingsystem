<?php
session_start();

// Include the database connection file
include 'db.php'; // Ensure that db.php contains the connection to your database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitPost'])) {

    // Sanitize and get the user input
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);

    // Validate input (check if fields are empty)
    if (empty($title) || empty($content) || empty($author)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: post.php"); // Redirect back to the form
        exit();
    }

    try {
        // Prepare the SQL query to insert the post into the database
        $query = "INSERT INTO posts (title, content, author, created_at) VALUES (:title, :content, :author, NOW())";
        $stmt = $pdo->prepare($query);

        // Bind parameters and execute the query
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        $stmt->execute();

        // Set a success message and redirect to the post view page
        $_SESSION['success'] = "Post created successfully!";
        header("Location: view_post.php?id=" . $pdo->lastInsertId()); // Redirect to the newly created post view page
        exit();

    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: post.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <style>
        /* Add your styling here */
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

        .form-container input,
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
        <h2>Create New Post</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success-message"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Post creation form -->
        <div class="form-container">
            <form action="post.php" method="POST">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="content" rows="6" placeholder="Post content..." required></textarea>
                <input type="text" name="author" placeholder="Your name" required>
                <button type="submit" name="submitPost">Create Post</button>
            </form>
        </div>
    </div>

</body>

</html>
