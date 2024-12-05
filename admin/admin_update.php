<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($product_id) {
    // Fetch current product details
    $query = "SELECT * FROM e_product_details WHERE product_id = $product_id";
    $result = mysqli_query($link, $query);
    if ($result) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching product details: " . mysqli_error($link);
        exit();
    }
} else {
    echo "Product ID is missing.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['price'];
    $product_description = $_POST['description'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $image_path = $product['image_path']; 

    // Handle the image upload if a new image is provided
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $upload_dir = 'uploads/';
        $image_path = $upload_dir . basename($image_name);

        // Validate image type
        $valid_image_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = mime_content_type($image_tmp);

        if (in_array($image_type, $valid_image_types)) {
            
            if (move_uploaded_file($image_tmp, $image_path)) {
                
                if (file_exists($product['image_path']) && $product['image_path'] != $image_path) {
                    unlink($product['image_path']);
                }
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
        }
    }

    // Prepare and execute update query
    $query = "UPDATE e_product_details 
              SET product_name = '$product_name', category = '$category', price = '$product_price', 
                  description = '$product_description', image_path = '$image_path', quantity = '$quantity'
              WHERE product_id = $product_id";
    
    if (mysqli_query($link, $query)) {
        header("Location: admin_home.php?update_message=" . urlencode('Record update successfully'));
    } else {
        echo "Error: " . mysqli_error($link);
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }
        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="mobile" <?php if ($product['category'] == 'mobile') echo 'selected'; ?>>Mobile</option>
                    <option value="electronics" <?php if ($product['category'] == 'electronics') echo 'selected'; ?>>Electronics</option>
                    <option value="appliances" <?php if ($product['category'] == 'appliances') echo 'selected'; ?>>Appliances</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="productImage">Upload Image (Optional):</label>
                <input type="file" id="productImage" name="product_image" accept="image/*">
                <br><br>
                <?php if ($product['image_path']) : ?>
                    <img src="<?php echo $product['image_path']; ?>" alt="Product Image" width="100">
                    <p>Current Image</p>
                <?php endif; ?>
            </div>
            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>
