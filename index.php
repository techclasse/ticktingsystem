<?php
session_start(); // Start session to track logged-in user

// Include the database connection file
include 'db.php'; // This includes the db connection using PDO

try {
    // Query to fetch all posts from the database
    $query = "SELECT * FROM posts ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all posts
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching posts: " . $e->getMessage();
    $posts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - Helpdesk System</title>
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

        .post-table {
            width: 100%;
            border-collapse: collapse;
        }

        .post-table th, .post-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .post-table th {
            background-color: #f2f2f2;
        }

        .post-table td {
            background-color: #fff;
        }

        .post-table a {
            color: #2575fc;
            text-decoration: none;
        }

        .post-table a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>

    <div class="container">
        <h2>Posts</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error-message"><?= $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="success-message"><?= $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Display posts in a table -->
        <table class="post-table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($posts)) {
                    echo "<tr><td colspan='5'>No posts available</td></tr>";
                } else {
                    $counter = 1;
                    foreach ($posts as $post) {
                        echo "<tr>
                            <td>{$counter}</td>
                            <td>{$post['title']}</td>
                            <td>{$post['author']}</td>
                            <td>{$post['created_at']}</td>
                            <td><a href='view_post.php?post_id={$post['post_id']}'>View</a></td>
                        </tr>";
                        $counter++;
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
