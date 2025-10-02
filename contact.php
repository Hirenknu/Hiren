<?php
include 'components/connection.php'; 
session_start();

// Check if the user is logged in
$user_id = $_SESSION['user_id'] ?? '';
$user_name = $_SESSION['user_name'] ?? '';

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle form submission securely
if (isset($_POST['submit-btn'])) {
    $name    = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email   = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $created_at = filter_var($_POST['created_at'] ?? '', FILTER_SANITIZE_STRING);
    try {
        $insert_msg = $conn->prepare("INSERT INTO message (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        $insert_msg->execute([$name, $email, $subject, $message, $created_at]);
        $success_msg = "Your message has been sent successfully!";
    } catch (PDOException $e) {
        $error_msg = "There was a problem sending your message. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Parth Cement Products</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body { font-family: 'Segoe UI', Tahoma, sans-serif; }
    .navbar { background-color: #2f3e46; }
    .navbar-brand, .nav-link { color: #fff !important; }
    .banner {
      background:#2c3e50;
      color:#fff;
      text-align:center;
      padding:4rem 1rem;
      border-radius:8px;
    }
    .form-container {
      max-width:700px;
      margin:3rem auto;
      background:#fff;
      padding:2rem;
      border-radius:10px;
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background:#2c3e50; color:#fff;
    }
    .btn-custom:hover {
      background:#1abc9c; color:#fff;
    }
    footer {
      background: #2f3e46;
      color: #ddd;
      padding: 20px 0;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand fw-bold" href="home.php">Parth Cement Product</a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="product.php">Products</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        <?php if ($user_id): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Banner -->
<div class="banner">
  <h1>Contact Us</h1>
  <p>We’d love to hear from you</p>
</div>

<!-- Contact Form -->
<div class="form-container">
  <h2 class="text-center mb-4">Send a Message</h2>

  <?php if (!empty($success_msg)): ?>
    <div class="alert alert-success"><?= $success_msg; ?></div>
  <?php elseif (!empty($error_msg)): ?>
    <div class="alert alert-danger"><?= $error_msg; ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Your Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Your Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Subject</label>
      <input type="text" name="subject" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Message</label>
      <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" name="submit-btn" class="btn btn-custom w-100">Send Message</button>
  </form>
</div>

<!-- Contact Details -->
<div class="container my-5">
  <div class="row text-center g-4">
    <div class="col-md-4">
      <i class="bx bxs-map-pin fs-1 text-success"></i>
      <h5 class="mt-2">Address</h5>
      <p>Bhujpar-Dhamadka, Kutch-Gujarat</p>
    </div>
    <div class="col-md-4">
      <i class="bx bxs-phone-call fs-1 text-success"></i>
      <h5 class="mt-2">Phone</h5>
      <p>+91 63524 85496</p>
    </div>
    <div class="col-md-4">
      <i class="bx bxs-envelope fs-1 text-success"></i>
      <h5 class="mt-2">Email</h5>
      <p>parthcementpro@gmail.com</p>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center">
  <p class="mb-0">© <?= date("Y"); ?> Parth Cement Product. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
