<?php
session_start();
include('config/db.php');

// 1. Security Check: Get the logged-in user's ID
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Get the JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'No cart data received']);
    exit;
}

$cart = $data['cart'];
$total = $data['total'];

// 2. Insert sale into 'sales' (Include user_id)
$sale_stmt = $conn->prepare("INSERT INTO sales (user_id, total_amount) VALUES (?, ?)");
$sale_stmt->bind_param("id", $user_id, $total);

if ($sale_stmt->execute()) {
    $sale_id = $conn->insert_id;

    // 3. Prepare insert for sale_items (Include user_id)
    $item_stmt = $conn->prepare("
        INSERT INTO sale_items (user_id, sale_id, product_name, quantity, price, subtotal) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    // 4. Prepare inventory update (Filter by user_id)
    $update_stmt = $conn->prepare("
        UPDATE products 
        SET volume_quantity = volume_quantity - ? 
        WHERE product_name = ? AND user_id = ?
    ");

    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['qty'];

        // Insert into sale_items
        $item_stmt->bind_param("iisidd", 
            $user_id,
            $sale_id, 
            $item['name'], 
            $item['qty'], 
            $item['price'], 
            $subtotal
        );
        $item_stmt->execute();

        // Deduct from products (Only for this user)
        $update_stmt->bind_param("isi", $item['qty'], $item['name'], $user_id);
        $update_stmt->execute();
    }

    echo json_encode(['success' => true, 'sale_id' => $sale_id]);

} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save sale']);
}

// Clean up
$sale_stmt->close();
$item_stmt->close();
$update_stmt->close();
?>

