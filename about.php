<?php
include 'components/connection.php'; 
session_start();

$user_id = $_SESSION['user_id'] ?? '';
$user_name = $_SESSION['user_name'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Parth Cement Products</title>
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
    .about-category .box {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .about-category img { width: 100%; height: auto; display: block; }
    .about-category .detail {
      position: absolute;
      bottom: 0;
      left: 0; right: 0;
      background: rgba(0,0,0,0.6);
      color: #fff;
      padding: 1rem;
      text-align: center;
    }
    .services .box-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }
    .services .box {
      background: #fff;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    .about .row { display:flex; flex-wrap:wrap; align-items:center; margin:3rem 0; }
    .about .img-box img { width:100%; border-radius:10px; }
    .testimonial-container {
      background:#f9f9f9;
      padding:3rem 1rem;
      text-align:center;
      border-radius:10px;
    }
    .testimonial-item { display:none; }
    .testimonial-item.active { display:block; }
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
        <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
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

<!-- Banner -->
<div class="banner">
  <h1>About Us</h1>
  <p>Learn more about Parth Cement Products</p>
</div>

<div class="container my-5">

  <!-- Products Showcase -->
  <div class="row about-category">
    <div class="col-md-3">
      <div class="box">
        <img src="img/cement-blocks.jpg" alt="Solid Blocks">
        <div class="detail"><span>Cement Products</span><h5>Solid Blocks</h5><a href="product.php" class="btn btn-light btn-sm">Shop Now</a></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box">
        <img src="img/paver-blocks.jpg" alt="Paver Blocks">
        <div class="detail"><span>Cement Products</span><h5>Paver Blocks</h5><a href="product.php" class="btn btn-light btn-sm">Shop Now</a></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box">
        <img src="img/tiles.jpg" alt="Concrete Tiles">
        <div class="detail"><span>Cement Products</span><h5>Concrete Tiles</h5><a href="product.php" class="btn btn-light btn-sm">Shop Now</a></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box">
        <img src="img/cover-blocks.jpg" alt="Cover Blocks">
        <div class="detail"><span>Cement Products</span><h5>Cover Blocks</h5><a href="product.php" class="btn btn-light btn-sm">Shop Now</a></div>
      </div>
    </div>
  </div>

  <!-- Why Choose Us -->
  <section class="services my-5">
    <div class="text-center mb-4">
      <h2>Why Choose Us</h2>
      <p>We deliver durable and high-quality cement-based solutions for construction, paving, and architectural needs.</p>
    </div>
    <div class="box-container">
      <div class="box"><img src="img/quality.png"><h5 class="mt-2">Premium Quality</h5><p>Strong, reliable, and long-lasting products.</p></div>
      <div class="box"><img src="img/support.png"><h5 class="mt-2">Customer Support</h5><p>24/7 assistance for all clients.</p></div>
      <div class="box"><img src="img/value.png"><h5 class="mt-2">Affordable Prices</h5><p>Best value for premium quality.</p></div>
      <div class="box"><img src="img/delivery.png"><h5 class="mt-2">Timely Delivery</h5><p>On-time supply for your projects.</p></div>
    </div>
  </section>

  <!-- About Content -->
  <div class="about row align-items-center my-5">
    <div class="col-md-6 img-box">
      <img src="img/factory.jpg" alt="Factory">
    </div>
    <div class="col-md-6 detail">
      <h2>Visit Our Production Unit</h2>
      <p>We provide paver blocks, interlocking tiles, cover blocks, and more. With advanced machinery and skilled staff, we ensure top-quality products for your construction needs.</p>
      <a href="view_products.php" class="btn btn-dark">Explore Products</a>
    </div>
  </div>

  <!-- Testimonials -->
  <div class="testimonial-container my-5">
    <h2>What Our Clients Say</h2>
    <div class="testimonial-item active">
      <img src="img/client1.jpg" class="rounded-circle mb-3" width="80">
      <h5>Ravi Patel</h5>
      <p>“High-quality paver blocks and timely delivery. Excellent service!”</p>
    </div>
    <div class="testimonial-item">
      <img src="img/client2.jpg" class="rounded-circle mb-3" width="80">
      <h5>Anita Sharma</h5>
      <p>“The solid cement blocks are extremely durable and perfect for our housing project.”</p>
    </div>
    <div class="testimonial-item">
      <img src="img/client3.jpg" class="rounded-circle mb-3" width="80">
      <h5>Construction Firm XYZ</h5>
      <p>“We always rely on Parth Cement Products for consistent quality and prompt service.”</p>
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
