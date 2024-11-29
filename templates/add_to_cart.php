<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

if (isset($_GET["product_id"]) && isset($_GET["user_id"])) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $product_id = mysqli_real_escape_string($link, $_GET["product_id"]);
    $user_id = mysqli_real_escape_string($link, $_GET["user_id"]);

    // Fetch product details
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";  
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);

        // Check if the product is already in the cart
        $check_query = "
            SELECT * FROM cart_details 
            WHERE user_id = '$user_id' AND product_id = '$product_id'
        ";
        $check_result = mysqli_query($link, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            // Insert into `cart_details` table if not already present
            $cart_query = "
                INSERT INTO cart_details (
                    user_id, product_id, product_name, price, description, category, image_path, quantity
                ) VALUES (
                    '$user_id', '$product_id', '$product_name', '$product_price', '$product_description', '$category', '$image_path', 1
                )";
            
            $cart_result = mysqli_query($link, $cart_query);
        }

        // Display the product details
        echo "
        <div class='container mt-5'>
            <div class='row justify-content-center align-items-center'>
                <!-- Image Section -->
                <div class='col-md-6 text-center'>
                    <img class='img-fluid' src='/E-commerce website/admin/$image_path' alt='$product_name' style='height: 350px; object-fit: cover; border-radius: 10px;'>
                </div>
                <!-- Content Section -->
                <div class='col-md-6'>
                    <h2 class='mb-4'>$product_name</h2>
                    <p><strong>Price:</strong> ₹$product_price</p>
                    <p><strong>Description:</strong></p>
                    <p>$product_description</p>
                    <div class='mt-4'>
                        <!-- Quantity Input -->
                        <div class='mb-3'>
                            <label for='quantity' class='form-label'><strong>Quantity:</strong></label>
                            <input type='number' id='quantity' class='form-control' value='1' min='1' style='width: 120px;'>
                        </div>
                        <!-- Buttons -->
                        <div class='d-flex gap-3'>
                            <a href='/E-commerce website/templates/delete_product.php?product_id=$product_id&user_id=$user_id' class='btn btn-danger' style='width: 120px;'>Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
    } else {
        echo "<p style='color: red;'>Product not found!</p>";
    }

    // Close the database connection
    mysqli_close($link);
} else {
    echo "<p style='color: red;'>Invalid request! Product ID or User ID missing.</p>";
}
?>
