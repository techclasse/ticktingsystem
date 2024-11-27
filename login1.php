<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Helpdesk System</title>

    <!-- Custom CSS -->
    <style>
        /* Reset default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
        /*    background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);*/
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #2575fc;
        }

        .form-group input[type="checkbox"] {
            width: auto;
        }

        .form-group input[type="checkbox"]:checked {
            accent-color: #2575fc;
        }

        .login-btn {
            background-color: #6a11cb;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
        }

        .login-btn:hover {
            background-color: #6a11cb;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .footer-text a {
            color: #2575fc;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media screen and (max-width: 600px) {
            .login-container {
                padding: 20px;
            }

            .login-container h2 {
                font-size: 1.5rem;
            }

            .login-btn {
                font-size: 14px;
            }
        }
        .title{
            color: navy;
            font-size: 30px;
            margin-right: 60px;
        }
    </style>
</head>

<body>
<div class="title">
<h2>DURGGA Helpdesk <br>Tickting System</h2>
    </div>
    <div class="login-container">
        <h2>Login to Helpdesk System</h2>

        <!-- Login Form -->
        <form action="login_process1.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Remember me</label>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

    
    </div>

</body>

</html>
