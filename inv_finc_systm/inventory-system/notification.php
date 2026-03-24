<?php
session_start(); 
include 'config/db.php';


if (!isset($_SESSION['user_id'])) {
    exit; 
}

$user_id = $_SESSION['user_id'];
$notifications = [];


$stmt_expired = $conn->prepare("
    SELECT product_name 
    FROM products 
    WHERE user_id = ? AND expiry_date < CURDATE()
");
$stmt_expired->bind_param("i", $user_id);
$stmt_expired->execute();
$expired_result = $stmt_expired->get_result();

if ($expired_result->num_rows > 0) {
    while ($row = $expired_result->fetch_assoc()) {
        $notifications[] = [
            "type" => "expired",
            "message" => htmlspecialchars($row['product_name']) . " has expired!"
        ];
    }
}

$stmt_soldout = $conn->prepare("
    SELECT product_name 
    FROM products 
    WHERE user_id = ? AND volume_quantity <= 0
");
$stmt_soldout->bind_param("i", $user_id);
$stmt_soldout->execute();
$soldout_result = $stmt_soldout->get_result();

if ($soldout_result->num_rows > 0) {
    while ($row = $soldout_result->fetch_assoc()) {
        $notifications[] = [
            "type" => "soldout",
            "message" => htmlspecialchars($row['product_name']) . " is sold out!"
        ];
    }
}

// Clean up
$stmt_expired->close();
$stmt_soldout->close();
?>


echo json_encode($notifications);
?>
