<?php
include 'config/db.php';

// Validate input
if (!isset($_POST['capital_amount'])) {
    echo "error";
    exit;
}

$capital = floatval($_POST['capital_amount']);

// Insert into DB
$sql = "INSERT INTO capital (capital_amount, start_date)
        VALUES ('$capital', CURDATE())";

if ($conn->query($sql)) {
    echo "success";
} else {
    echo "error";
}
?>
