<?php
include 'components/connection.php'; 
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['place_order'])) {

    $name    = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number  = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email   = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = filter_var($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ', ' . $_POST['pincode'], FILTER_SANITIZE_STRING);
    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method       = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    $varify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
    $varify_cart->execute([$user_id]);

    try {
        if (isset($_GET['get_id'])) {
            $get_product = $conn->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
            $get_product->execute([$_GET['get_id']]);
            if ($get_product->rowCount() > 0) {
                $fetch_p = $get_product->fetch(PDO::FETCH_ASSOC);
                $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
                header('location: order.php');
                exit();
            } else {
                $warning_msg[] = 'Product not found.';
            }
        } elseif ($varify_cart->rowCount() > 0) {
            while($f_cart = $varify_cart->fetch(PDO::FETCH_ASSOC)){
                $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);
            }
            // Clear user's cart after placing the order
            $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id=?");
            $delete_cart->execute([$user_id]);
            header('location: order.php');
            exit();
        } else {
            $warning_msg[] = 'Your cart is empty.';
        }
    } catch (PDOException $e) {
        $warning_msg[] = 'Something went wrong. Please try again!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Parth Cement Products</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        .main { padding: 2rem; }
        .banner { text-align:center; background:#2c3e50; color:#fff; padding:3rem 1rem; border-radius:8px; }
        .banner h1 { margin:0; font-size:2rem; }
        .title2 { margin:1rem 0; text-align:center; color:#555; }
        .checkout .row { display:flex; flex-wrap:wrap; gap:2rem; margin-top:2rem; }
        .checkout form { flex:2; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .checkout .summary { flex:1; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .checkout .input-field { margin-bottom:1rem; }
        .checkout .input-field p { margin-bottom:0.3rem; font-size:14px; }
        .checkout .input-field input, .checkout .input-field select { width:100%; padding:10px; border-radius:6px; border:1px solid #ccc; font-size:14px; }
        .btn { background:#2c3e50; color:#fff; padding:12px; border:none; border-radius:6px; cursor:pointer; width:100%; font-size:16px; transition:0.3s; }
        .btn:hover { background:#1abc9c; }
        .summary h3 { margin-bottom:1rem; }
        .summary .flex { display:flex; gap:1rem; margin-bottom:1rem; align-items:center; }
        .summary .image { width:70px; height:70px; object-fit:cover; border-radius:6px; }
        .grand-total { font-weight:bold; font-size:1.1rem; margin-top:1rem; text-align:right; }
        .empty { text-align:center; color:#999; margin-top:1rem; }
    </style>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Checkout Summary</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a> / Checkout
        </div>

        <section class="checkout">
            <div class="row">
                <form method="post">
                    <h3>Billing Details</h3>
                    <div class="flex">
                        <div class="box">
                            <div class="input-field"><p>Name *</p><input type="text" name="name" required></div>
                            <div class="input-field"><p>Phone *</p><input type="number" name="number" required></div>
                            <div class="input-field"><p>Email *</p><input type="email" name="email" required></div>
                            <div class="input-field"><p>Payment Method *</p>
                                <select name="method">
                                    <option value="cash on delivery">Cash on Delivery</option>
                                    <option value="credit/debit card">Credit/Debit Card</option>
                                    <option value="net banking">Net Banking</option>
                                    <option value="UPI/RuPay">UPI/RuPay</option>
                                    <option value="Paytm">Paytm</option>
                                </select>
                            </div>
                            <div class="input-field"><p>Address Type *</p>
                                <select name="address_type">
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                </select>
                            </div>
                        </div>

                        <div class="box">
                            <div class="input-field"><p>Address Line 1 *</p><input type="text" name="flat" required></div>
                            <div class="input-field"><p>Address Line 2 *</p><input type="text" name="street" required></div>
                            <div class="input-field"><p>City *</p><input type="text" name="city" required></div>
                            <div class="input-field"><p>Country *</p><input type="text" name="country" required></div>
                            <div class="input-field"><p>Pincode *</p><input type="text" name="pincode" required maxlength="6"></div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn">Place Order</button>
                </form>

                <div class="summary">
                    <h3>My Bag</h3>
                    <?php
                    $grand_total = 0;
                    if (isset($_GET['get_id'])) {
                        $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
                        $stmt->execute([$_GET['get_id']]);
                        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
                        $stmt->execute([$user_id]);
                        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $items = [];
                        foreach($cart_items as $c){
                            $pstmt = $conn->prepare("SELECT * FROM products WHERE id=?");
                            $pstmt->execute([$c['product_id']]);
                            $product = $pstmt->fetch(PDO::FETCH_ASSOC);
                            $product['qty'] = $c['qty'];
                            $items[] = $product;
                        }
                    }

                    if (!empty($items)) {
                        foreach($items as $item){
                            $sub_total = $item['price'] * ($item['qty'] ?? 1);
                            $grand_total += $sub_total;
                            ?>
                            <div class="flex">
                                <img src="<?=$item['image']?>" class="image">
                                <div>
                                    <h3 class="name"><?=$item['name']?></h3>
                                    <p class="price"><?=$item['price']?> X <?= $item['qty'] ?? 1 ?></p>
                                </div>
                            </div>
                        <?php }
                    } else {
                        echo '<p class="empty">Your cart is empty.</p>';
                    }
                    ?>
                    <div class="grand-total">Total: $<?= $grand_total ?>/-</div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
