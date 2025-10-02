<?php
include 'components/connection.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("location: home.php");
    exit();
}

// Login process
if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass  = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $_SESSION['user_id']    = $row['id'];
        $_SESSION['user_name']  = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        header('location: home.php');
    } else {
        $message = "Incorrect email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parth Cement Product - Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                  url('images/login-bg.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Segoe UI", sans-serif;
    }
    .login-box {
      background: #fff;
      border-radius: 15px;
      padding: 40px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.3);
      text-align: center;
    }
    .login-box img {
      width: 80px;
      margin-bottom: 15px;
    }
    .login-box h2 {
      margin-bottom: 20px;
      font-weight: bold;
      color: #2c3e50;
    }
    .form-control {
      border-radius: 10px;
      padding: 12px;
    }
    .btn-custom {
      background: #2c3e50;
      color: #fff;
      border-radius: 10px;
      padding: 12px;
      width: 100%;
      font-weight: bold;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background: #1a252f;
    }
    .register-link {
      margin-top: 15px;
      font-size: 14px;
    }
    .alert {
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <img src="images/pcp.jpg" alt="Parth Cement Logo">
    <h2>Login to Continue</h2>

    <?php if (!empty($message)): ?>
      <div class="alert alert-danger"><?= $message; ?></div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required maxlength="50">
      </div>
      <div class="mb-3">
        <input type="password" name="pass" class="form-control" placeholder="Enter your password" required maxlength="50">
      </div>
      <input type="submit" name="submit" value="Login Now" class="btn btn-custom">
    </form>

    <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
