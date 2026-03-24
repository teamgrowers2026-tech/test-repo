<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type  = $_POST['product_type'];
    $name  = $_POST['product_name'];
    $vol   = $_POST['volume_quantity'];
    $exp   = $_POST['expiry_date'];
    $price = $_POST['price'];

    
    $stmt = $conn->prepare("INSERT INTO products (user_id, product_type, product_name, volume_quantity, expiry_date, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssd", $user_id, $type, $name, $vol, $exp, $price);
    
    if ($stmt->execute()) {
        // Optional: Success message
    }
    $stmt->close();
}

// 3. Fetch Products (FILTERED BY USER_ID)
$stmt_get = $conn->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY product_type, product_name");
$stmt_get->bind_param("i", $user_id);
$stmt_get->execute();
$result = $stmt_get->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['product_type']][] = $row;
}
$stmt_get->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>
<main class="main-content">
    <div class="form-container">
        <h2>Add Product</h2>
        <form method="POST" class="product-form">
        <label>Product Type</label>
        <select name="product_type" required>
            <option>Food and Drinks</option>
            <option>Toiletries and Personal Care</option>
            <option>Household and Cleaning Supplies</option>
            <option>Other Items</option>
        </select>

        <label>Product Name</label>
        <input type="text" name="product_name" required>

        <label>Volume/Quantity</label>
        <input type="text" name="volume_quantity" required>

        <label>Expiry Date</label>
        <input type="date" name="expiry_date">

        <label>Price</label>
        <input type="number" name="price" step="0.01" required>

        <button type="submit" class="save-btn">Save Product</button>
    </form>
    </div>
    <hr><br>

    <h2>Product List</h2>

    <?php foreach ($products as $type => $items): ?>
        <h3 class="category-title"><?= $type ?></h3>

        <div class="product-grid">
            <?php foreach ($items as $p): ?>
                <div class="product-card">
                    <b><?= $p['product_name'] ?></b><br>
                    Qty: <?= $p['volume_quantity'] ?><br>
                    Exp: <?= $p['expiry_date'] ?><br>
                    Price: ₱<?= number_format($p['price'], 2) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <br><br>
    <?php endforeach; ?>

</main>
<?php include('includes/footer.php'); ?>
</body>
</html>
