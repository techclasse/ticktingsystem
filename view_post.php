<?php
session_start(); // Start session to track logged-in user

// Include the database connection file
include 'db.php'; // This includes the db connection using PDO

// Check if the post ID is provided in the URL
if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    $_SESSION['error'] = "Invalid post ID.";
    header("Location: index.php"); // Redirect to homepage or posts listing page
    exit();
}

// Get the post ID from the URL
$post_id = $_GET['post_id'];

try {
    // Query to fetch the post by ID
    $query = "SELECT * FROM posts WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the post details
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if the post exists
    if (!$post) {
        $_SESSION['error'] = "Post not found.";
        header("Location: index.php"); // Redirect to homepage or posts listing page
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching post: " . $e->getMessage();
    header("Location: index.php"); // Redirect to homepage or posts listing page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
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

        .post-content {
            margin-bottom: 30px;
        }

        .post-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .post-author {
            font-size: 16px;
            color: #777;
            margin-bottom: 10px;
        }

        .post-date {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }

        .post-body {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2575fc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #6a11cb;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>View Post</h2>

        <!-- Display error message if any -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="post-content">
            <div class="post-title"><?= htmlspecialchars($post['title']); ?></div>
            <div class="post-author">By: <?= htmlspecialchars($post['author']); ?></div>
            <div class="post-date">Created on: <?= htmlspecialchars($post['created_at']); ?></div>
            <div class="post-body"><?= nl2br(htmlspecialchars($post['content'])); ?></div>
        </div>

        <a href="index.php" class="back-button">Back to Posts</a>
    </div>

</body>
</html>
