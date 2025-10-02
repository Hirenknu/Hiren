<?php
include 'components/connection.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php"); 
    exit();
}

if (isset($_POST['submit'])) {
    $id     = unique_id();
    $name   = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email  = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass   = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $cpass  = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);

    // Check if email exists
    $select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $select_user->execute([$email]);

    if ($select_user->rowCount() > 0) {
        $error_msg = "Email already exists. Please login instead.";
    } elseif ($pass !== $cpass) {
        $error_msg = "Passwords do not match. Please confirm again.";
    } else {
        // Secure hash password
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        $insert_user = $conn->prepare("INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)");
        $insert_user->execute([$id, $name, $email, $hashed_pass]);

        // Auto login after registration
        $_SESSION['user_id']    = $id;
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;

        header("Location: home.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Parth Cement Products</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; background: #f5f5f5; }
        .main-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .form-container { background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 400px; width: 100%; text-align: center; }
        .form-container img { width: 80px; margin-bottom: 1rem; }
        .form-container h1 { margin-bottom: 10px; color: #2e2e2e; }
        .form-container p { font-size: 14px; color: #777; margin-bottom: 1.5rem; }
        .input-field { margin-bottom: 1.2rem; text-align: left; }
        .input-field p { font-size: 14px; margin-bottom: 6px; color: #333; }
        .input-field input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 15px; }
        .btn { background: #2c3e50; color: #fff; border: none; padding: 12px; border-radius: 8px; width: 100%; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #1abc9c; }
        .form-container a { color: #1abc9c; text-decoration: none; font-weight: 500; }
        .error-msg { color: #e74c3c; margin-bottom: 1rem; font-size: 14px; }
    </style>
</head>
<body>
    <div class="main-container">
        <section class="form-container">
            <img src="images/pcp.jpg" alt="Parth Cement Logo">
            <h1>Create Account</h1>
            <p>Join Parth Cement Products and explore our quality construction materials.</p>

            <?php if (!empty($error_msg)): ?>
                <div class="error-msg"><?= $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="input-field">
                    <p>Your Name <sup>*</sup></p>
                    <input type="text" name="name" required placeholder="Enter your full name" maxlength="50">
                </div>
                <div class="input-field">
                    <p>Your Email <sup>*</sup></p>
                    <input type="email" name="email" required placeholder="Enter your email" maxlength="50">
                </div>
                <div class="input-field">
                    <p>Password <sup>*</sup></p>
                    <input type="password" name="pass" required placeholder="Enter password" maxlength="50">
                </div>
                <div class="input-field">
                    <p>Confirm Password <sup>*</sup></p>
                    <input type="password" name="cpass" required placeholder="Re-enter password" maxlength="50">
                </div>
                <input type="submit" name="submit" value="Register Now" class="btn">
                <p style="margin-top: 1rem;">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </section>
    </div>
</body>
</html>
