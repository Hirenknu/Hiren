<?php
include 'components/connection.php'; 
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];
    $qty = 1;

    $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $check_cart->execute([$user_id, $product_id]);

    if ($check_cart->rowCount() > 0) {
        $warning_msg[] = 'Product already exists in your cart';
    } elseif ($check_cart->rowCount() > 20) {
        $warning_msg[] = 'Cart is full';
    } else {
        $select_price = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        $insert_cart = $conn->prepare("INSERT INTO cart (id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)");
        $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
        $success_msg[] = 'Product added to cart successfully';
    }
}

// Delete from wishlist
if (isset($_POST['delete_item'])) {
    $wishlist_id = $_POST['wishlist_id'];

    $check_item = $conn->prepare("SELECT * FROM wishlist WHERE id = ?");
    $check_item->execute([$wishlist_id]);

    if ($check_item->rowCount() > 0) {
        $delete_item = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
        $delete_item->execute([$wishlist_id]);
        $success_msg[] = "Wishlist item deleted successfully";
    } else {
        $warning_msg[] = 'Wishlist item already deleted';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parth Cement Product - Wishlist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .banner {
      background: url('img/wishlist-banner.jpg') center/cover no-repeat;
      color: #fff;
      text-align: center;
      padding: 80px 20px;
    }
    .banner h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .products .box {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 20px;
      background: #fff;
      transition: all 0.3s ease-in-out;
    }
    .products .box:hover {
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
      transform: translateY(-5px);
    }
    .products img {
      max-height: 200px;
      object-fit: contain;
      margin-bottom: 15px;
    }
    .products .button {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 15px;
    }
    .products .button button,
    .products .button a {
      border: none;
      background: #f8f9fa;
      padding: 10px 12px;
      border-radius: 50%;
      font-size: 1.2rem;
      color: #333;
      transition: background 0.3s;
    }
    .products .button button:hover,
    .products .button a:hover {
      background: #007bff;
      color: #fff;
    }
    .products h3 {
      font-size: 1.1rem;
      font-weight: bold;
    }
    .products .price {
      color: #28a745;
      font-weight: bold;
    }
    .empty {
      text-align: center;
      font-size: 1.2rem;
      color: #666;
      padding: 50px 0;
    }
  </style>
</head>
<body>

<?php include 'components/header.php'; ?>

<!-- Banner -->
<div class="banner">
  <h1>My Wishlist</h1>
  <p>Save the products you love and get them anytime</p>
</div>

<!-- Breadcrumb -->
<div class="container py-3">
  <a href="home.php" class="text-decoration-none text-dark">Home</a> 
  <span class="text-muted">/ Wishlist</span>
</div>

<!-- Wishlist Products -->
<section class="products py-5">
  <div class="container">
    <h2 class="text-center fw-bold mb-5">Products in Your Wishlist</h2>
    <div class="row g-4">
      <?php
      $select_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);

      if ($select_wishlist->rowCount() > 0) {
          while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
              $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
              $select_products->execute([$fetch_wishlist['product_id']]);

              if ($select_products->rowCount() > 0) {
                  $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="col-md-4">
        <form method="post" class="box text-center">
          <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
          <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">

          <img src="<?= $fetch_products['image']; ?>" alt="<?= $fetch_products['name']; ?>" class="img-fluid">

          <div class="button">
            <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
            <a href="view_page.php?pid=<?= $fetch_products['id']; ?>"><i class="bx bxs-show"></i></a>
            <button type="submit" name="delete_item" onclick="return confirm('Delete this item?');"><i class="bx bx-x"></i></button>
          </div>

          <h3 class="name"><?= $fetch_products['name']; ?></h3>
          <p class="price">$<?= $fetch_products['price']; ?> /-</p>
          <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>" class="btn btn-primary w-100 mt-2">Buy Now</a>
        </form>
      </div>
      <?php
              }
          }
      } else {
          echo '<p class="empty">No products added yet!</p>';
      }
      ?>
    </div>
  </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js"></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
