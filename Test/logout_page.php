<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - Vintage Vault</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6e047c, #8B5E3C);
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .logout-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 350px;
        }
        h2 {
            margin-bottom: 10px;
        }
        p {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #ffffff;
            color: #6e047c;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #f9f5f0;
        }
        .loader {
            border: 4px solid white;
            border-top: 4px solid #8B5E3C;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="logout-container">
    <h2>You have successfully logged out</h2>
    <p>Redirecting you to the login page...</p>
    <div class="loader"></div>
    <a href="index.html" class="btn">Go to Login</a>
</div>

<script>
    setTimeout(() => {
        window.location.href = "index.html"; // Auto-redirect after 5 seconds
    }, 5000);
</script>

</body>
</html>
