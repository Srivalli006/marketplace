<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "vintage_vault";    

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Turn on error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'] ?? '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href = 'signup.html';</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM signup WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.location.href = 'signup.html';</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO signup (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Redirecting to Sign In page.'); window.location.href = 'index.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'signup.html';</script>";
        }
    }

    // Close statement
    $stmt->close();
}

// Check if form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo "<script>alert('Error: Email or Password is empty!'); window.location.href = 'index.html';</script>";
        exit();
    }

    // Fetch user details
    $stmt = $conn->prepare("SELECT id, name, password FROM signup WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            echo "<script>alert('Login successful!'); window.location.href = 'homes.html';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password! Please try again.'); window.location.href = 'index.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email! Please sign up.'); window.location.href = 'signup.html';</script>";
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>
