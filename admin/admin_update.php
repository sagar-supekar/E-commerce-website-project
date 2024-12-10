<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to sign-in page
    header("Location: /E-commerce website/templates/welcome.php");
    exit();
}
include('admin_header.php');
// if (!isset($_SESSION['username'])) {
//     // If not logged in, redirect to sign-in page
//     header("Location: /E-commerce website/templates/welcome.php");
//     exit();
// }
// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

if ($product_id) {
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

// Initialize error messages for each field
$errorMessages = [
    'product_name' => '',
    'price' => '',
    'description' => '',
    'category' => '',
    'quantity' => '',
    'product_image' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name']);
    $product_price = trim($_POST['price']);
    $product_description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $quantity = trim($_POST['quantity']);
    $image_path = $product['image_path'];

    // Validate fields
    if (empty($product_name)) {
        $errorMessages['product_name'] = "Product name is required.";
    }

    if (empty($category)) {
        $errorMessages['category'] = "Category is required.";
    } elseif (!in_array($category, ['mobile', 'electronics', 'appliances'])) {
        $errorMessages['category'] = "Invalid category selected.";
    }

    if (empty($product_price)) {
        $errorMessages['price'] = "Price is required.";
    } elseif (!is_numeric($product_price) || $product_price <= 0) {
        $errorMessages['price'] = "Price must be a positive number.";
    }

    if (empty($quantity)) {
        $errorMessages['quantity'] = "Quantity is required.";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $errorMessages['quantity'] = "Quantity must be a non-negative number.";
    }

    if (empty($product_description)) {
        $errorMessages['description'] = "Description is required.";
    }

    // Validate image upload if a new image is provided
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $upload_dir = 'uploads/';
        $image_path = $upload_dir . basename($image_name);

        $valid_image_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = mime_content_type($image_tmp);

        if (in_array($image_type, $valid_image_types)) {
            if (!move_uploaded_file($image_tmp, $image_path)) {
                $errorMessages['product_image'] = "Failed to upload image.";
            } else {
                if (file_exists($product['image_path']) && $product['image_path'] != $image_path) {
                    unlink($product['image_path']);
                }
            }
        } else {
            $errorMessages['product_image'] = "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
        }
    }

    // Proceed only if no errors
    if (!array_filter($errorMessages)) {
        $query = "UPDATE e_product_details 
                  SET product_name = ?, category = ?, price = ?, description = ?, image_path = ?, quantity = ?
                  WHERE product_id = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "sssdsii", $product_name, $category, $product_price, $product_description, $image_path, $quantity, $product_id);

        if (mysqli_stmt_execute($stmt)) {
            
            header("Location: admin_home.php?update_message=" . urlencode('Record updated successfully'));
            exit();
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
    <title>Update Product</title>
    <style>
        .main-container {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .update {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            width: 100%;
        }

        .form-container {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 100%;
        }

        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        img {
            margin-top: 10px;
            border-radius: 5px;
            max-width: 100px;
            max-height: 100px;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 15px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body class="main-container">
    <div class="d-flex justify-content-start mx-2">
        <a href="admin_home.php" 
        class="btn-close" 
        aria-label="Close" 
        style="font-size: 24px; text-decoration: none;"></a>

    </div>
    <div class="update">
        <div class="form-container">
            <h2>Update Product</h2>
            <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
        <span style="color: red;"><?php echo $errorMessages['product_name']; ?></span>
    </div>

    <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="mobile" <?php if ($product['category'] == 'mobile') echo 'selected'; ?>>Mobile</option>
            <option value="electronics" <?php if ($product['category'] == 'electronics') echo 'selected'; ?>>Electronics</option>
            <option value="appliances" <?php if ($product['category'] == 'appliances') echo 'selected'; ?>>Appliances</option>
        </select>
        <span style="color: red;"><?php echo $errorMessages['category']; ?></span>
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
        <span style="color: red;"><?php echo $errorMessages['price']; ?></span>
    </div>

    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>">
        <span style="color: red;"><?php echo $errorMessages['quantity']; ?></span>
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <span style="color: red;"><?php echo $errorMessages['description']; ?></span>
    </div>

    <div class="form-group">
        <label for="productImage">Upload Image (Optional):</label>
        <input type="file" id="productImage" name="product_image" accept="image/*">
        <br><br>
        <?php if ($product['image_path']) : ?>
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image">
            <p>Current Image</p>
        <?php endif; ?>
        <span style="color: red;"><?php echo $errorMessages['product_image']; ?></span>
    </div>

    <button type="submit">Update Product</button>
</form>

        </div>
    </div>
</body>

</html>

