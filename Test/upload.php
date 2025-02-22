<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "vintage_vault";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product-name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Handle Image Upload
    $target_dir = "uploads/"; // Folder where images will be stored
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if not exists
    }

    $image_name = basename($_FILES["image-upload"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name; // Rename image to prevent conflicts
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.'); window.location.href = 'seller.html';</script>";
        exit();
    }

    // Move uploaded file to destination folder
    if (move_uploaded_file($_FILES["image-upload"]["tmp_name"], $target_file)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO sell (product_name, price, description, category, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsss", $product_name, $price, $description, $category, $target_file);

        if ($stmt->execute()) {
            echo "<script>alert('Product uploaded successfully!'); window.location.href = 'seller.html';</script>";
        } else {
            echo "<script>alert('Error uploading product.'); window.location.href = 'seller.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error uploading image. Please try again.'); window.location.href = 'seller.html';</script>";
    }
}

// Close connection
$conn->close();
?>
