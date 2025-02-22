<?php
$host = "localhost"; // Change if using a different host
$user = "root"; // Change to your database username
$password = ""; // Change to your database password
$database = "vintage_vault"; // Change to your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the 'sell' table
$sql = "SELECT id, product_name, price, description, image_path FROM sell ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if products exist
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product" data-id="' . $row['id'] . '">
                <img src="' . $row['image_path'] . '" width="250" height="250" alt="' . htmlspecialchars($row['product_name']) . '">
                <h3>' . htmlspecialchars($row['product_name']) . '</h3>
                <p>Price: $' . number_format($row['price'], 2) . '</p>
                <button class="wishlist-btn" onclick="addToWishlist(' . $row['id'] . ', \'' . addslashes($row['product_name']) . '\', ' . $row['price'] . ', this)">Add to Wishlist</button>
              </div>';
    }
} else {
    echo "<p>No products available.</p>";
}

// Close connection
$conn->close();
?>
