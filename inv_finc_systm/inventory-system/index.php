<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="main-content">
    <div class="dashboard-header">
    <h2>30-Day Financial Overview</h2>
    <button class="add-expense-btn" onclick="openCapitalForm()">Add Capital</button>

<!-- Floating Capital Form -->
<div id="capitalFormOverlay" class="overlay">
    <div class="capital-form">
        <h2>Add Capital</h2>

        <form action="add_capital.php" method="POST" onsubmit="closeCapitalForm()">
            
            <label>Capital Amount</label>
            <input type="number" name="capital_amount" required placeholder="Enter amount">

            <label>Start Date</label>
            <input type="date" name="start_date" required>

            <div class="form-buttons">
                <button type="submit" class="submit-btn">Add</button>
                <button type="button" class="cancel-btn" onclick="closeCapitalForm()">Cancel</button>
            </div>
        </form>

    </div>
</div>

    <?php
include 'config/db.php';

// 1. Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// 2. Get the latest capital entry ONLY for this user
$stmt_cap = $conn->prepare("
    SELECT capital_amount, start_date
    FROM capital
    WHERE user_id = ?
    ORDER BY id DESC
    LIMIT 1
");
$stmt_cap->bind_param("i", $user_id);
$stmt_cap->execute();
$capital_result = $stmt_cap->get_result();
$capital_row = $capital_result->fetch_assoc();

if ($capital_row) {
    $capital = $capital_row['capital_amount'];
    $start_date = $capital_row['start_date'];

    // End date = 30 days after start
    $end_date = date('Y-m-d', strtotime($start_date . " +30 days"));

    // 3. Get total sales for THIS user within their specific 30-day window
    $stmt_sales = $conn->prepare("
        SELECT SUM(total_amount) AS total_sales
        FROM sales
        WHERE user_id = ? 
        AND DATE(date_time) BETWEEN ? AND ?
    ");
    $stmt_sales->bind_param("iss", $user_id, $start_date, $end_date);
    $stmt_sales->execute();
    $sales_result = $stmt_sales->get_result();
    $sales_row = $sales_result->fetch_assoc();

    $total_sales = $sales_row['total_sales'] ?? 0;
    $assessment = $total_sales - $capital;

    $stmt_sales->close();
} else {
    // No capital yet for this user
    $capital = 0;
    $total_sales = 0;
    $assessment = 0;
    $start_date = "N/A";
    $end_date = "N/A";
}

$stmt_cap->close();
?>
 

    <div class="category-grid">

        <div class="cat-card">
            <h3>₱<?php echo number_format($capital, 2); ?></h3>
            <p>Capital</p>
        </div>

        <div class="cat-card">
            <h3><?php echo $start_date; ?></h3>
            <p>Start Date</p>
        </div>

        <div class="cat-card">
            <h3><?php echo $end_date; ?></h3>
            <p>End Date (30 Days)</p>
        </div>

        <div class="cat-card">
            <h3>₱<?php echo number_format($total_sales, 2); ?></h3>
            <p>Total Sales (30 Days)</p>
        </div>

        <div class="cat-card" style="background: <?php echo ($assessment >= 0 ? '#d4f8d4' : '#ffd4d4'); ?>;">
            <h3>
                <?php 
                if ($assessment >= 0) {
                    echo "₱" . number_format($assessment, 2) . " Gain";
                } else {
                    echo "₱" . number_format(abs($assessment), 2) . " Loss";
                }
                ?>
            </h3>
            <p>Assessment</p>
        </div>

    </div>
</div>

    </div>

    <section class="content-grid">
        <div class="sales-chart">
            <h3>Daily Sales Chart</h3>

<?php
include 'config/db.php';


$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("
    SELECT 
        DATE(date_time) AS sale_day,
        SUM(total_amount) AS total_sales
    FROM sales
    WHERE user_id = ?
    GROUP BY DATE(date_time)
    ORDER BY sale_day ASC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$query = $stmt->get_result();


$dates = [];
$totals = [];

while ($row = $query->fetch_assoc()) {
    $dates[] = $row['sale_day'];
    $totals[] = $row['total_sales'];
}

$dates_json = json_encode($dates);
$totals_json = json_encode($totals);

$stmt->close();
?>

<canvas id="salesChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo $dates_json; ?>,
        datasets: [{
            label: "Daily Sales (₱)",
            data: <?php echo $totals_json; ?>,
            borderWidth: 2,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

        </div>

    <div class="top-categories">
    <h3>Top Selling Items</h3>

    <?php
include 'config/db.php';

$user_id = $_SESSION['user_id'];

$stmt_top = $conn->prepare("
    SELECT 
        si.product_name,
        p.product_type,
        SUM(si.quantity) AS total_sold
    FROM sale_items si
    LEFT JOIN products p 
        ON si.product_name = p.product_name 
        AND si.user_id = p.user_id
    WHERE si.user_id = ?
    GROUP BY si.product_name, p.product_type
    ORDER BY total_sold DESC
    LIMIT 6
");
$stmt_top->bind_param("i", $user_id);
$stmt_top->execute();
$top_items = $stmt_top->get_result();
?>
     <div class="category-grid">
        <?php while ($row = $top_items->fetch_assoc()): ?>
            <div class="cat-card">
                <h4><?php echo $row['product_name']; ?></h4>
                <p><?php echo $row['product_type'] ?? "Unknown Category"; ?></p>
                <span class="sold-info">Sold: <?php echo $row['total_sold']; ?></span>
            </div>
        <?php endwhile; ?>
    </div>
        </div>
</div>

    </section>
</main>

<?php include('includes/footer.php'); ?>
