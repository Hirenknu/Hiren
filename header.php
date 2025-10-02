<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'] ?? '';
?>

<!-- Header -->
<header class="header shadow-sm">
  <div class="container d-flex align-items-center justify-content-between py-2">

    <!-- Logo -->
    <a href="home.php" class="logo d-flex align-items-center text-decoration-none">
      <img src="img/logo.jpg" alt="Parth Cement Logo" class="me-2" style="height:50px;">
      <span class="fw-bold text-dark fs-4">Parth Cement</span>
    </a>

    <!-- Navbar (Desktop) -->
    <nav class="navbar d-none d-lg-flex">
      <a href="home.php" class="nav-link px-3 text-dark fw-semibold">Home</a>
      <a href="about.php" class="nav-link px-3 text-dark fw-semibold">About Us</a>
      <a href="Products.php" class="nav-link px-3 text-dark fw-semibold">Products</a> <!-- ✅ Shop page -->
      <a href="order.php" class="nav-link px-3 text-dark fw-semibold">Orders</a>
      <a href="contact.php" class="nav-link px-3 text-dark fw-semibold">Contact</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="nav-link px-3 text-dark fw-semibold">Logout</a>
      <?php else: ?>
        <a href="login.php" class="nav-link px-3 text-dark fw-semibold">Login</a>
      <?php endif; ?>
    </nav>

    <!-- Icons -->
    <div class="icons d-flex align-items-center gap-3">
      <i class="bx bxs-user fs-4 text-dark" id="user-btn" style="cursor:pointer;"></i>

      <?php
        $count_wishlist_items = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
        $count_wishlist_items->bind_param("i", $user_id);
        $count_wishlist_items->execute();
        $result_wishlist = $count_wishlist_items->get_result();
        $total_wishlist_items = $result_wishlist->num_rows;
      ?>
      <a href="wishlist.php" class="position-relative text-dark fs-4">
        <i class="bx bx-heart"></i>
        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
          <?= $total_wishlist_items; ?>
        </span>
      </a>

      <?php
        $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $count_cart_items->bind_param("i", $user_id);
        $count_cart_items->execute();
        $result_cart = $count_cart_items->get_result();
        $total_cart_items = $result_cart->num_rows;
      ?>
      <a href="cart.php" class="position-relative text-dark fs-4">
        <i class="bx bx-cart-download"></i>
        <span class="badge bg-primary position-absolute top-0 start-100 translate-middle rounded-pill">
         <?= $total_cart_items; ?>
        </span>
      </a>

      <!-- Mobile Menu -->
      <i class="bx bx-menu fs-2 d-lg-none" id="menu-btn" style="cursor:pointer;"></i>
    </div>
  </div>

  <!-- User Box -->
  <div class="user-box bg-light p-3 rounded shadow-sm position-absolute end-0 mt-2 me-3" style="display:none; z-index:1000;">
    <p class="mb-1">👤 Username: <strong>
      <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?>
    </strong></p>
    <p class="mb-3">📧 Email: <strong>
      <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Not available'; ?>
    </strong></p>
    <?php if(isset($_SESSION['user_name']) && isset($_SESSION['user_email'])){ ?>
      <form method="post">
        <button type="submit" name="logout" class="btn btn-danger w-100">Logout</button>
      </form>
    <?php } else { ?>
      <a href="login.php" class="btn btn-primary w-100 mb-2">Login</a>
      <a href="register.php" class="btn btn-outline-dark w-100">Register</a>
    <?php } ?>
  </div>
</header>

<!-- Custom CSS -->
<style>
  .navbar .nav-link {
    transition: color 0.3s;
  }
  .navbar .nav-link:hover {
    color: #007bff;
  }
  .user-box {
    width: 250px;
  }
</style>

<!-- JS for toggle user box -->
<script>
  document.getElementById('user-btn').addEventListener('click', function() {
    const box = document.querySelector('.user-box');
    box.style.display = box.style.display === 'block' ? 'none' : 'block';
  });
</script>
