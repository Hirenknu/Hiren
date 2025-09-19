<?php
include 'components/connection.php'; 
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit-btn'])) {
    // Retrieve and sanitize user input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);


$sql = "INSERT INTO message (`name`, `email`, `subject`, `message`) VALUES ('$name', '$email', '$subject', '$message')";

try {
    // Execute the query
    $conn->exec($sql);
    echo '<script>
            swal("Success!", "Your message has been sent!", "success");
          </script>';
} catch (PDOException $e) {
    echo '<script>
            swal("Error!", "There was a problem sending your message. Please try again.", "error");
          </script>';

    }
}
?>

<style type="text/css">
    <?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Green Coffee - Home Page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Contact Us</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a><span>Contact Us</span>
        </div>
        <section class="services">
            <div class="box-container">
                <div class="box">
                    <img src="img/icon2.png" alt="Great Savings">
                    <div class="detail">
                        <h3>Great Savings</h3>
                        <p>Save big every order</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon1.png" alt="24/7 Support">
                    <div class="detail">
                        <h3>24/7 Support</h3>
                        <p>One-on-one support</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon0.png" alt="Gift Vouchers">
                    <div class="detail">
                        <h3>Gift Vouchers</h3>
                        <p>Vouchers on every festival</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon.png" alt="Express Delivery">
                    <div class="detail">
                        <h3>Express Delivery</h3>
                        <p>Dropship city</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="form-container">
            <form method="post">
                <div class="title">
                    <img src="img/download.png" class="logo" alt="Logo">
                    <h1>Send a Message</h1>
                </div>
                <div class="input-field">
                    <p>Your Name:</p>
                    <input type="text" name="name" required>
                </div>
                <div class="input-field">
                    <p>Your Email:</p>
                    <input type="email" name="email" required>
                </div>
                <div class="input-field">
                    <p>Subject:</p>
                    <input type="text" name="subject" required>
                </div>
                <div class="input-field">
                    <p>Your Message:</p>
                    <textarea name="message" required></textarea>
                </div>
                <button type="submit" name="submit-btn" class="btn">Send Message</button>
            </form>
        </div>
        <div class="address">
            <div class="title">
                <img src="img/download.png" class="logo" alt="Logo">
                <h1>Contact Details</h1>
               
            </div>
            <div class="box-container">
                <div class="box">
                    <i class="bx bxs-map-pin"></i>
                    <div>
                        <h4>Address</h4>
                        <p>1082 Marigold Lane, Coral Way</p>
                    </div>
                </div>
                <div class="box">
                    <i class="bx bxs-phone-call"></i>
                    <div>
                        <h4>Phone Number</h4>
                        <p>99046 58215</p>
                    </div>
                </div>
                <div class="box">
                    <i class="bx bxs-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>hbsatvara@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'components/footer.php'; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
