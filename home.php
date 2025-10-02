<?php
include 'components/connection.php';
session_start();

// Check if user logged in
$user_id = $_SESSION['user_id'] ?? '';
$user_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parth Cement Product - Home</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar {
      background-color: #2f3e46;
    }
    .navbar-brand, .nav-link {
      color: #fff !important;
    }
    .hero {
      background: url('cement-bg.jpg') no-repeat center center/cover;
      color: #fff;
      height: 85vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
    }
    .hero::after {
      content: "";
      position: absolute;
      top:0; left:0; right:0; bottom:0;
      background: rgba(0,0,0,0.5);
    }
    .hero > div {
      position: relative;
      z-index: 1;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .hero p {
      font-size: 1.2rem;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    <a class="navbar-brand d-flex align-items-center" href="#">
  <img src="images/pcp.jpg" alt="Parth Cement Products Logo"
       style="height:80px; width:80px; border-radius:30%; object-fit:cover; margin-right:20px;">
  <span class="fw-bold">Parth Cement Product</span>
</a>  
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="product.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <?php if ($user_id): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div>
    <h1>Building Trust With Strength</h1>
    <p>High-quality cement products for a stronger tomorrow</p>
    <a href="#products" class="btn btn-light btn-lg mt-3">Explore Products</a>
    <?php if ($user_id): ?>
      <p class="mt-3">Welcome, <strong><?= htmlspecialchars($user_name); ?></strong>!</p>
    <?php endif; ?>
  </div>
</section>

<!-- About Section -->
<section id="about" class="py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <img src="about-cement.jpg" alt="About Parth Cement" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <h2 class="fw-bold">About Us</h2>
        <p>
          Parth Cement Product is committed to delivering premium quality cement solutions. 
          With years of expertise and innovation, we provide durable and reliable products 
          that meet the highest industry standards.
        </p>
        <a href="#contact" class="btn btn-dark">Get in Touch</a>
      </div>
    </div>
  </div>
</section>

<!-- Products Section -->
<section id="products" class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Our Products</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-3">
          <img src="product1.jpg" class="card-img-top rounded" alt="Product 1">
          <div class="card-body">
            <h5 class="card-title">Cement Blocks</h5>
            <p class="card-text">Durable and eco-friendly blocks suitable for all construction needs.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <img src="product2.jpg" class="card-img-top rounded" alt="Product 2">
          <div class="card-body">
            <h5 class="card-title">Concrete Pipes</h5>
            <p class="card-text">High-strength pipes designed for long-lasting infrastructure projects.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <img src="product3.jpg" class="card-img-top rounded" alt="Product 3">
          <div class="card-body">
            <h5 class="card-title">Paver Blocks</h5>
            <p class="card-text">Stylish and strong paver blocks ideal for pathways and driveways.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Contact Us</h2>
    <p>We’d love to hear from you. Reach out for inquiries or product details.</p>
    <a href="mailto:info@parthcement.com" class="btn btn-primary btn-lg">Email Us</a>
  </div>
</section>

<!-- Footer -->
<footer class="text-center">
  <p class="mb-0">© <?php echo date("Y"); ?> Parth Cement Product. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
