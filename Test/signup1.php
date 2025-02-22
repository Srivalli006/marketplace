<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "vintage_vault"; // Ensure database name is correct

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check if connection is successful
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Validate input fields
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href='signup.html';</script>";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the users table exists
    $table_check = $conn->query("SHOW TABLES LIKE 'signup'");
    if ($table_check->num_rows == 0) {
        echo "<script>alert('Error: Users table does not exist! Please create the table in the database.');</script>";
        exit();
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM signup WHERE email = ?");
    if ($check_email === false) {
        die("Prepare failed: " . $conn->error);
    }

    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email already registered! Try a different one.'); window.location.href='signup.html';</script>";
        exit();
    }

    $check_email->close();

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO signup (name, email, phone, password) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Redirecting to login.'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='signup.html';</script>";
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>
