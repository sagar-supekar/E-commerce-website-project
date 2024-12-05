<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('admin_header.php');

// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("Product not found.");
    }

    // Fetch the product details
    $row = mysqli_fetch_assoc($result);
    $product_name = $row['product_name'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $description = $row['description']; 
    $category = $row['category']; 
    $image_url = $row['image_path']; 
} else {
    die("Product ID is missing.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product Details</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Product Details</h2>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title"><?php echo $product_name; ?></h3>
            
            <!-- Display Product Image -->
            <div class="text-center">
                <?php if ($image_url): ?>
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $product_name; ?>" class="img-fluid" style="max-width: 300px; height: auto;">
                <?php else: ?>
                    <p>No image available.</p>
                <?php endif; ?>
            </div>

            <p><strong>Product ID:</strong> <?php echo $product_id; ?></p>
            <p><strong>Category:</strong> <?php echo $category; ?></p>
            <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
            <p><strong>Price:</strong> ₹. <?php echo $price; ?></p>
            <p><strong>Description:</strong> <?php echo $description; ?></p>

            <a href="admin_home.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS (required for Bootstrap 5 modals) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
mysqli_close($link);
?>
