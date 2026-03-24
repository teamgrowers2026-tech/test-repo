<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY product_type, product_name");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="main-content">
    <div class="pos-container">

        <!-- PRODUCTS -->
        <div class="product-list" id="productList">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card"
                         data-name="<?= htmlspecialchars($row['product_name']) ?>"
                         data-price="<?= $row['price'] ?>">
                        <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                        <p>₱<?= number_format($row['price'], 2) ?></p>
                        <small><?= htmlspecialchars($row['product_type']) ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>

        <!-- CART -->
        <div class="cart-section">
            <div class="cart-items" id="cartItems"></div>

            <div class="checkout">
                <div class="total" id="totalAmount">Total: ₱0.00</div>
                <button id="checkoutBtn">Complete Sale</button>
            </div>
        </div>

    </div>
</main>

<?php include('includes/footer.php'); ?>

<script>
const productCards = document.querySelectorAll('.product-card');
const cartItems = document.getElementById('cartItems');
const totalAmount = document.getElementById('totalAmount');
let cart = [];

/* ADD TO CART */
productCards.forEach(card => {
    card.addEventListener('click', () => {
        const name = card.dataset.name;
        const price = parseFloat(card.dataset.price);

        const existing = cart.find(item => item.name === name);
        if (existing) existing.qty++;
        else cart.push({ name, price, qty: 1 });

        updateCart();
    });
});

/* UPDATE CART */
function updateCart() {
    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.qty;

        cartItems.innerHTML += `
            <div class="cart-item">
                <span>${item.name}</span>

                <div class="qty-controls">
                    <button onclick="decreaseQty(${index})">-</button>
                    <span>${item.qty}</span>
                    <button onclick="increaseQty(${index})">+</button>
                </div>

                <span>₱${(item.price * item.qty).toFixed(2)}</span>
            </div>
        `;
    });

    totalAmount.textContent = `Total: ₱${total.toFixed(2)}`;
}

function increaseQty(i) {
    cart[i].qty++;
    updateCart();
}

function decreaseQty(i) {
    cart[i].qty--;
    if (cart[i].qty <= 0) cart.splice(i, 1);
    updateCart();
}

/* CHECKOUT */
document.getElementById('checkoutBtn').addEventListener('click', () => {
    if (cart.length === 0) {
        alert('Cart is empty!');
        return;
    }

    const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    fetch('save_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart, total })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(`✅ Sale saved successfully!\nSale ID: ${data.sale_id}`);
            cart = [];
            updateCart();
        } else {
            alert('❌ Error saving sale.');
        }
    })
    .catch(err => console.error(err));
});
</script>

</body>
</html>
