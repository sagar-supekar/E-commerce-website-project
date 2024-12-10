<?php
session_start();
include('admin_header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to sign-in page
    header("Location: /E-commerce website/templates/welcome.php");
    exit();
}
// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$product_name = $product_price = $product_description = $category = $quantity = $subcategory = "";
$product_name_err = $subcategory_err = $product_price_err = $product_description_err = $category_err = $quantity_err = $image_err = "";
$success_msg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $is_valid = true;

    // Product name validation
    if (empty($_POST['product_name'])) {
        $product_name_err = "Product name is required.";
        $is_valid = false;
    } else {
        $product_name = htmlspecialchars($_POST['product_name']);
    }

    // Price validation
    if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
        $product_price_err = "Valid price is required.";
        $is_valid = false;
    } else {
        $product_price = htmlspecialchars($_POST['price']);
    }

    // Quantity validation
    if (empty($_POST['quantity']) || !is_numeric($_POST['quantity']) || $_POST['quantity'] <= 0) {
        $quantity_err = "Valid quantity is required.";
        $is_valid = false;
    } else {
        $quantity = htmlspecialchars($_POST['quantity']);
    }

    // Description validation
    if (empty($_POST['description'])) {
        $product_description_err = "Description is required.";
        $is_valid = false;
    } else {
        $product_description = htmlspecialchars($_POST['description']);
    }

    // Category validation
    if (empty($_POST['category']) || $_POST['category'] == 'Select Category') {
        $category_err = "Category is required.";
        $is_valid = false;
    } else {
        $category = htmlspecialchars($_POST['category']);
    }
    if (empty($_POST['subcategory']) || $_POST['subcategory'] == 'Select Subcategory') {
        $subcategory_err = "Subcategory is required.";
        $is_valid = false;
    } else {
        $subcategory = htmlspecialchars($_POST['subcategory']);
    }
    // Image upload validation
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $upload_dir = 'uploads/';
        $image_path = $upload_dir . basename($image_name);
        //echo $image_path;
        echo $image_tmp;
        $valid_image_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = mime_content_type($image_tmp);

        if (!in_array($image_type, $valid_image_types)) {
            $image_err = "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
            $is_valid = false;
        } elseif (!move_uploaded_file($image_tmp, $image_path)) {
            $image_err = "Failed to upload image.";
            $is_valid = false;
        }
    } else {
        $image_err = "Image upload is required.";
        $is_valid = false;
    }

    // Insert into database if valid
    if ($is_valid) {
        $query = "INSERT INTO e_product_details (product_name, category, subcategory, price, description, image_path, quantity) 
                  VALUES ('$product_name', '$category', '$subcategory', '$product_price', '$product_description', '$image_path', '$quantity')";
        $result = mysqli_query($link, $query);

        if ($result) {
            header("Location: admin_home.php?add_product_success=" . urlencode('New Product Added Successfully'));
            $success_msg = "Product added successfully.";
            // Reset fields after success
            $product_name = $product_price = $product_description = $category = $quantity = $subcategory = "";
        } else {
            echo "Error: " . mysqli_error($link);
        }
    }
}

mysqli_close($link);
?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        .main-container{
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0 auto;
            padding: 0;
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
            margin: 0 auto;
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

        input,
        select,
        textarea {
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

<body class="main-container">
    <div class="form-container my-2">
    <div class="d-flex justify-content-start">
        <a href="admin_home.php" 
        class="btn-close" 
        aria-label="Close" 
        style="font-size: 24px; text-decoration: none;"></a>

    </div>
        <h2>Add Product</h2>
        <?php if (!empty($success_msg)) echo "<p style='color: green;'>$success_msg</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
                <small style="color: red;"><?php echo $product_name_err; ?></small>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category">
                    <option value="Select Category" <?php echo ($category == 'Select Category') ? 'selected' : ''; ?>>Select Category</option>
                    <option value="mobile" <?php echo ($category == 'mobile') ? 'selected' : ''; ?>>Mobile</option>
                    <option value="electronics" <?php echo ($category == 'electronics') ? 'selected' : ''; ?>>Electronics</option>
                    <option value="appliances" <?php echo ($category == 'appliances') ? 'selected' : ''; ?>>Appliances</option>
                </select>
                <small style="color: red;"><?php echo $category_err; ?></small>
            </div>
            <div class="form-group">
                <label for="subcategory">Subcategory:</label>
                <select id="subcategory" name="subcategory">
                    <option value="Select Subcategory" <?php echo ($subcategory == 'Select Subcategory') ? 'selected' : ''; ?>>Select Subcategory</option>
                    <option value="keypad" <?php echo ($subcategory == 'keypad') ? 'selected' : ''; ?>>Keypad</option>
                    <option value="touchpad" <?php echo ($subcategory == 'touchpad') ? 'selected' : ''; ?>>Touchpad</option>
                    <option value="laptop" <?php echo ($subcategory == 'laptop') ? 'selected' : ''; ?>>Laptop</option>
                    <option value="earbuds" <?php echo ($subcategory == 'earbuds') ? 'selected' : ''; ?>>Earbuds</option>
                    <option value="washing_machine" <?php echo ($subcategory == 'washing_machine') ? 'selected' : ''; ?>>Washing Machine</option>
                    <option value="refrigerator" <?php echo ($subcategory == 'refrigerator') ? 'selected' : ''; ?>>Refrigerator</option>
                    <option value="smartwatch" <?php echo ($subcategory == 'smartwatch') ? 'selected' : ''; ?>>Smartwatch</option>
                </select>
                <small style="color: red;"><?php echo $subcategory_err; ?></small>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product_price); ?>">
                <small style="color: red;"><?php echo $product_price_err; ?></small>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
                <small style="color: red;"><?php echo $quantity_err; ?></small>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product_description); ?></textarea>
                <small style="color: red;"><?php echo $product_description_err; ?></small>
            </div>
            <div class="form-group">
                <label for="productImage">Upload Image:</label>
                <input type="file" id="productImage" name="product_image" accept="image/*">
                <small style="color: red;"><?php echo $image_err; ?></small>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>

</body>

</html>