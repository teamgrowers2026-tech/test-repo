<?php
session_start();
include('config/db.php');

$error = '';
if (isset($_POST['login'])) {
    // Trim input to prevent accidental spaces from failing the login
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // 1. Use Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, userName, password FROM users WHERE userName = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 2. Verify hashed password
        if (password_verify($password, $user['password'])) {
            // 3. Store the Primary Key 'id' in the session
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['userName'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        // Using a generic error message is safer for security
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventory & Finance Manager</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="login-body">

    <div class="login-container">
        <div class="login-card">
            <h2>Inventory.sc</h2>
            <p class="subtitle">Sign in to manage your inventory</p>

            <?php if($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="login-form">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>

                <button type="submit" name="login" class="btn-login">Login</button>
            </form>

            <p class="signup-text">
                Don’t have an account? <a href="signup.php">Sign up</a>
            </p>

            <p class="footer-text">© <?php echo date("Y"); ?> Inventory.sc</p>
        </div>
    </div>

</body>
</html>
