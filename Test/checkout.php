<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vintage_vault";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $payment = $_POST["payment"];
    $order_data = json_decode($_POST["order_data"], true);

    if (!is_array($order_data) || empty($order_data)) {
        echo "<script>alert('No items selected for checkout.'); window.location.href='checkout.html';</script>";
        exit();
    }

    $order_items = [];
    $order_ids = [];

    $stmt = $conn->prepare("SELECT id, name, price FROM sell WHERE id = ?");
    foreach ($order_data as $item) {
        $item_id = $item['id'];
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $order_items[] = "{$row['name']} (${$row['price']})";
            $order_ids[] = $row['id'];
        }
    }
    $stmt->close();

    if (empty($order_items)) {
        echo "<script>alert('Selected items are not available.'); window.location.href='checkout.html';</script>";
        exit();
    }

    $order_items_str = implode(", ", $order_items);

    $stmt = $conn->prepare("INSERT INTO orders (name, email, phone, address, payment, order_items, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $name, $email, $phone, $address, $payment, $order_items_str);
    $stmt->execute();
    $stmt->close();

    foreach ($order_ids as $id) {
        $stmt = $conn->prepare("DELETE FROM sell WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>
        alert('Order placed successfully!');
        localStorage.removeItem('checkoutItems');
        window.location.href='home.html';
    </script>";
}

$conn->close();
?>
