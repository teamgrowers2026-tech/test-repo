<?php
session_start();
include('config/db.php');

$message = '';

if (isset($_POST['add_product'])) {
    $type = $_POST['product_type'];
    $name = $_POST['product_name'];
    $volume = $_POST['volume_quantity'];
    $expiry = $_POST['expiry_date'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (product_type, product_name, volume_quantity, expiry_date, price)
            VALUES ('$type', '$name', '$volume', '$expiry', '$price')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "✅ Product added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Inventor.io</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include('includes/sidebar.php'); ?>

<main class="main-content">
    <div class="form-container">
        <h2>Add New Product</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" class="product-form">

             <label>Product Type</label>
            <select name="product_type" required>
                <option value="" disabled selected>Select product type</option>
                <option value="Food and Drinks">Food and Drinks</option>
                <option value="Toiletries and Personal Care">Toiletries and Personal Care</option>
                <option value="Household and Cleaning Supplies">Household and Cleaning Supplies</option>
                <option value="Other Items">Other Items</option>
            </select>

            <label>Product Name</label>
            <input type="text" name="product_name" placeholder="Enter product name" required>

            <label>Volume / Quantity</label>
            <input type="text" name="volume_quantity" placeholder="e.g., 500ml, 1 box" required>

            <label>Expiry Date</label>
            <input type="date" name="expiry_date">

            <label>Price</label>
            <input type="number" step="0.01" name="price" placeholder="Enter price" required>

            <button type="submit" name="add_product" class="btn-primary">Add Product</button>
        </form>
    </div>
</main>

</body>
</html>
