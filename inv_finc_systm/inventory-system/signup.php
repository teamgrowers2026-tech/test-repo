<?php
include('config/db.php');
session_start();

$message = '';

if (isset($_POST['signup'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['userName'];
    $address = $_POST['address'];
    $position = $_POST['position'];
    $sex = $_POST['sex'];
    $date_started = $_POST['date_started'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // File upload
    $profile_pic = "";
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
        $profile_pic = $target_file;
    }

    if ($password !== $confirm) {
        $message = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check = "SELECT * FROM users WHERE userName='$email'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            $message = "Username is taken.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = "INSERT INTO loginform (fullname, userName, address, position, sex, date_started, profile_pic, password)
                       VALUES ('$fullname', '$email', '$address', '$position', '$sex', '$date_started', '$profile_pic', '$hashedPassword')";
            if ($conn->query($insert)) {
                $message = "Account created successfully! You can now log in.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Inventory.sc</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">

    <div class="login-container">
        <div class="login-card">
            <h2>Create Account</h2>
            <p class="subtitle">Join Inventory.sc today</p>

            <?php if($message): ?>
                <div class="error-msg"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="signup-form">

  <div class="form-row">
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="fullname" required>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="userName" required>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Address</label>
      <input type="text" name="address">
    </div>
    <div class="form-group">
      <label>Position</label>
      <input type="text" name="position">
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Sex</label>
      <select name="sex">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
    </div>
    <div class="form-group">
      <label>Date Started</label>
      <input type="date" name="date_started" required>
    </div>
  </div>

  <label>Profile Picture</label>
  <input type="file" name="profile_pic" accept="image/*">

  <div class="form-row">
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" name="confirm" required>
    </div>
  </div>

  <button type="submit" name="signup" class="btn-login">Sign Up</button>

</form>

            <p class="signup-text">
                Already have an account? <a href="login.php">Login</a>
            </p>

            <p class="footer-text">© <?php echo date("Y"); ?> Inventory.sc</p>
        </div>
    </div>

</body>
</html>
