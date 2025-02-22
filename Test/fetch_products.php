<?php
$host = "localhost"; 
$user = "root"; 
$password = ""; 
$database = "vintage_vault"; 

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "SELECT id, product_name, price, description, image_path FROM sell ORDER BY created_at DESC";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($products);
$conn->close();
?>
