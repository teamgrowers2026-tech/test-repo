<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Inventor.io</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include('includes/sidebar.php'); ?>

<main class="main-content">
    <h2>My Profile</h2>
    <div class="profile-card">
        <img src="<?php echo $user['profile_pic'] ?: 'assets/img/default-avatar.png'; ?>" alt="Profile" class="profile-pic">
        <p><strong>Full Name:</strong> <?php echo $user['fullname']; ?></p>
        <p><strong>Username:</strong> <?php echo $user['userName']; ?></p>
        <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
        <p><strong>Position:</strong> <?php echo $user['position']; ?></p>
        <p><strong>Sex:</strong> <?php echo $user['sex']; ?></p>
        <p><strong>Date Started:</strong> <?php echo $user['date_started']; ?></p>
    </div>
</main>

<?php include('includes/footer.php'); ?>
</body>
</html>
