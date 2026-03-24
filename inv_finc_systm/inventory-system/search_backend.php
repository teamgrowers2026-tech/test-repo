<?php
session_start();
include("config/db.php");


if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to search products.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
$q = isset($_GET['q']) ? $_GET['q'] : '';
$searchTerm = "%$q%";

$sql = "
    SELECT * FROM products 
    WHERE user_id = ? 
    AND (
        product_name LIKE ? 
        OR volume_quantity LIKE ? 
        OR product_type LIKE ?
    )
    LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// 3. Display Results
if ($result->num_rows == 0) {
    echo "<p>No results found for <b>" . htmlspecialchars($q) . "</b>.</p>";
    exit;
}

while ($row = $result->fetch_assoc()) {
    echo "
        <div style='padding:10px 0; border-bottom:1px solid #ddd;'>
            <h3 style='margin:0; font-size:18px; font-weight:600;'>" . htmlspecialchars($row['product_name']) . "</h3>
            <p style='margin:2px 0; font-size:14px;'>Quantity: " . htmlspecialchars($row['volume_quantity']) . "</p>
            <p style='margin:2px 0; font-size:14px;'>Category: " . htmlspecialchars($row['product_type']) . "</p>
        </div>
    ";
}

$stmt->close();
?>

