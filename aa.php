<?php
// Database connection settings
$host = 'localhost';
$db = 'shop_db';
$user = 'root';
$pass = '';

// Create a new MySQLi instance
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $targetDir = "uploads/";

    // Check if the uploads directory exists, if not, create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $originalFileName = basename($_FILES["image"]["name"]);
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $uniqueFileName = uniqid() . '.' . $fileExtension;
    $targetFile = $targetDir . $uniqueFileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) { // 500 KB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $product_detail = $_POST['product_detail'];
            $status = 'active';

            // Store the relative path to the image (now using the unique file name)
            $relativeFilePath = $targetFile;

            // Insert product details into the database
            $stmt = $conn->prepare("INSERT INTO products (name, price, image, product_detail, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsss", $name, $price, $relativeFilePath, $product_detail, $status);
            $stmt->execute();
            $stmt->close();

            echo "The file " . htmlspecialchars($originalFileName) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch all products from the database
$sql = 'SELECT id, name, price, image, product_detail, status FROM products WHERE status = ?';
$stmt = $conn->prepare($sql);
$status = 'active';
$stmt->bind_param('s', $status);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f7f7f7;
        }
        .form-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .full-screen-table {
            padding: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="centered-form">
        <div class="form-container">
            <h1>Upload New Product</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" required>

                <label for="product_detail">Product Details:</label>
                <textarea name="product_detail" id="product_detail" rows="4" required></textarea>

                <label for="image">Select image to upload:</label>
                <input type="file" name="image" id="image" required>

                <input type="submit" value="Upload Product" name="submit">
            </form>
        </div>
    </div>

    <div class="full-screen-table">
        <h1>Product List</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Product Details</th>
                <th>Status</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['id']); ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td>
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php endif; ?>
                </td>
                <td><?php echo nl2br(htmlspecialchars($product['product_detail'])); ?></td>
                <td><?php echo htmlspecialchars($product['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>