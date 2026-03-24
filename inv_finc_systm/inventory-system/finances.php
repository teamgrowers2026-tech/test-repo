<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('config/db.php');
$user_id = $_SESSION['user_id']; // Current logged-in user

// SAVE EXPENSE
if (isset($_POST['save_expense'])) {
    $label = $_POST['expense_label'];
    $amount = floatval($_POST['amount']);
    $date = $_POST['expense_date'];

    // Multi-user: Include user_id in the insert
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, expense_label, amount, expense_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $label, $amount, $date);
    $stmt->execute();
    $stmt->close();

    echo "<script>location.href='finances.php';</script>";
    exit();
}
?>

<?php include('includes/header.php');?>
<?php include('includes/sidebar.php');?>

<main class="main-content">
    <div class="finance-header">
        <h2>Finances Overview</h2>
        <button id="openExpenseForm" class="add-expense-btn">+ Add Expense</button>
    </div>

    <div id="expenseForm" class="floating-form hidden">
        <h3>Add Expense</h3>
        <form method="POST" action="">
            <label>Expense Label</label>
            <input type="text" name="expense_label" required>
            <label>Amount (₱)</label>
            <input type="number" step="0.01" name="amount" required>
            <label>Date</label>
            <input type="date" name="expense_date" value="<?php echo date('Y-m-d'); ?>" required>
            <button type="submit" name="save_expense" class="save-btn">Save</button>
            <button type="button" id="closeExpenseForm" class="close-btn">Cancel</button>
        </form>
    </div>

    <h3 class="section-title">Recorded Expenses</h3>
    <div class="expense-group">
        <?php
        // Multi-user: Filter groups by user_id
        $stmt = $conn->prepare("SELECT expense_date, SUM(amount) AS total_day FROM expenses WHERE user_id = ? GROUP BY expense_date ORDER BY expense_date DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $rows = $stmt->get_result();

        while ($r = $rows->fetch_assoc()) {
            echo "
            <div class='expense-day'>
                <h4>{$r['expense_date']}</h4>
                <p>Total: ₱" . number_format($r['total_day'], 2) . "</p>
                <div class='expense-items'>";

            // Multi-user: Filter items by user_id AND date
            $stmt_items = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? AND expense_date = ?");
            $stmt_items->bind_param("is", $user_id, $r['expense_date']);
            $stmt_items->execute();
            $items = $stmt_items->get_result();

            while ($i = $items->fetch_assoc()) {
                echo "
                <div class='expense-item'>
                    <span>" . htmlspecialchars($i['expense_label']) . "</span>
                    <strong>₱" . number_format($i['amount'], 2) . "</strong>
                </div>";
            }
            $stmt_items->close();
            echo "</div></div>";
        }
        $stmt->close();
        ?>
    </div>

    <h3 class="section-title">Monthly Assessment</h3>
    <div class="calendar">
    <?php
    $year = date("Y");
    $month = date("m");
    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($d = 1; $d <= $days; $d++) {
        $date = "$year-$month-" . str_pad($d, 2, "0", STR_PAD_LEFT);

        // Multi-user: Filter daily sales by user_id
        $stmt_s = $conn->prepare("SELECT SUM(total_amount) AS s FROM sales WHERE user_id = ? AND DATE(date_time) = ?");
        $stmt_s->bind_param("is", $user_id, $date);
        $stmt_s->execute();
        $sales = $stmt_s->get_result()->fetch_assoc()['s'] ?? 0;
        $stmt_s->close();

        // Multi-user: Filter daily expenses by user_id
        $stmt_e = $conn->prepare("SELECT SUM(amount) AS e FROM expenses WHERE user_id = ? AND expense_date = ?");
        $stmt_e->bind_param("is", $user_id, $date);
        $stmt_e->execute();
        $exp = $stmt_e->get_result()->fetch_assoc()['e'] ?? 0;
        $stmt_e->close();

        $assessment = $sales - $exp;
        $color = ($assessment >= 0) ? "#d4f8d4" : "#ffd4d4";

        echo "
        <div class='calendar-box' style='background:$color'>
            <h4>$d</h4>
            <p>₱" . number_format($assessment, 2) . "</p>
        </div>";
    }
    ?>
    </div>
</main>

<?php include('includes/footer.php'); ?>

<script>
// OPEN FLOATING FORM
document.getElementById("openExpenseForm").onclick = () => {
    document.getElementById("expenseForm").classList.remove("hidden");
};

// CLOSE FLOATING FORM
document.getElementById("closeExpenseForm").onclick = () => {
    document.getElementById("expenseForm").classList.add("hidden");
};
</script>
