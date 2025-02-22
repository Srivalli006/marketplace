<?php
session_start();
require 'db.php'; // Include DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and Password are required!'); window.location.href = 'index.html';</script>";
        exit();
    }

    // Fetch user data from database
    $stmt = $conn->prepare("SELECT id, name, password FROM signup WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Start session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            echo "<script>alert('Login successful! Redirecting...'); window.location.href = 'home.html';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href = 'index.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location.href = 'signup.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
