<?php
// Database connection (PDO)
$host = "localhost";
$dbname = "parth_cement_db";
$username = "root";           
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch products (safe query, handles missing description)
$stmt = $conn->prepare("SELECT id, name, price, image
                        FROM product 
                        ORDER BY id DESC");
$stmt->execute();
$product = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Start session for navbar login/logout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parth Cement Products</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
      body  { font-family: 'Segoe UI', Tahoma, sans-serif; }
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

<!-- Header -->
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

<!-- Products Section -->
<section class="products-section">
    <div class="container">
        <h2 class="section-title">Our Products</h2>
        <div class="product-grid">
            <?php if (!empty($product)): ?>
                <?php foreach ($product as $row): ?>
                    <div class="product-card">
                        <img src="<?= !empty($row['image']) ? htmlspecialchars($row['image']) : 'img/placeholder.png'; ?>" 
                             alt="<?= htmlspecialchars($row['name']); ?>">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($row['name']); ?></h3>
                            <!-- Only show description if column exists -->
                            <?php if (!empty($row['description'])): ?>
                                <p><?= htmlspecialchars($row['description']); ?></p>
                            <?php endif; ?>
                            <div class="price">₹<?= number_format($row['price'], 2); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">No products available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="text-center">
  <p class="mb-0">© <?= date("Y"); ?> Parth Cement Product. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
