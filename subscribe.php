<?php
session_start();
if (!isset($_SESSION['customer_logged_in'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $delivery_frequency = $_POST['delivery_frequency'];
    $start_date = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO subscriptions (customer_id, product_id, quantity, delivery_frequency, start_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$customer_id, $product_id, $quantity, $delivery_frequency, $start_date]);

    header("Location: dashboard.php");
    exit();
}

$product_id = $_GET['product_id'];
$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$product_id]);
$product = $product->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscribe to Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Subscribe to <?php echo $product['name']; ?></h1>
    <form method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="number" name="quantity" placeholder="Quantity" required>
        <select name="delivery_frequency" required>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
        </select>
        <button type="submit">Subscribe</button>
    </form>
</body>
</html>