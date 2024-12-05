<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Getting POST data
    $product_name = $_POST['product_name'];
    $product_price = $_POST['price'];
    $product_description = $_POST['description'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    // Handling the image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $upload_dir = 'uploads/';
        $image_path = $upload_dir . basename($image_name);

    
        $valid_image_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = mime_content_type($image_tmp);

        if (in_array($image_type, $valid_image_types)) {
            
            if (move_uploaded_file($image_tmp, $image_path)) {
                $query = "INSERT INTO e_product_details (product_name, category, price, description, image_path,quantity) 
                          VALUES ('$product_name', '$category', '$product_price', '$product_description', '$image_path','$quantity')";
                          
               
                $result = mysqli_query($link, $query);
                
                if ($result) {
                    echo "Product added successfully.";
                } else {
                    echo "Error: " . mysqli_error($link);
                }
            } else {
                echo "Failed to upload image.";
            }
        } else {
            echo "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "Error with the image upload.";
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
    <div class="d-flex justify-content-start">
        <a href="admin_home.php" 
        class="btn-close" 
        aria-label="Close" 
        style="font-size: 24px; text-decoration: none;">X</a>

    </div>
        <h2>Add Product</h2>
        <form  method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="mobile">Select Category</option>
                    <option value="mobile">Mobile</option>
                    <option value="electronics">Electronics</option>
                    <option value="appliances">Appliances</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price"  required>
            </div>
            <div class="form-group">
                <label for="price">Quantity:</label>
                <input type="number" id="price" name="quantity"  required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="productImage">Upload Image:</label>
                <input type="file" id="productImage" name="product_image" accept="image/*" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
