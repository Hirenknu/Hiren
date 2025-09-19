<?php
session_start();
$host = 'localhost';  
$user = 'root';       
$pass = '';          
$db = 'shop_db';      

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle login logic
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the admin user
    $sql = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if ($admin['password'] === $password) {
            $_SESSION['admin'] = $username;  // Store username in session
            header("Location: admin.php");  
            exit;
        } else {
            $error = "Invalid Username or Password!";
        }
    } else {
        $error = "Invalid Username or Password!";
    }
}

// Display admin dashboard if logged in
if (isset($_SESSION['admin'])) {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
            .header { background-color: #333; color: #fff; padding: 15px; text-align: center; }
            .content { padding: 20px; }
            .logout-btn { color: white; background-color: red; padding: 5px 10px; text-decoration: none; }
        </style>
    </head>
    <body>
        <div class="header">
           
            <h1>Welcome, Admin <?php echo $_SESSION['admin']; ?></h1> 
            <a href="?logout=true" class="logout-btn">Logout</a>
        </div>

        <div class="content">
            <center><h1>Admin Dashboard</h1></center>
        </div>

        <div class="content">
            <?php include('admin-upload.php'); ?>
        </div>
    </body>
    </html>
<?php
} else { 
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
            .login-container { width: 300px; margin: 100px auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            h2 { text-align: center; }
            input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
            button { width: 100%; padding: 10px; background-color: #333; color: white; border: none; border-radius: 5px; }
            .error { color: red; text-align: center; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Admin Login</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </body>
    </html>
<?php
}
?>