<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

if (isset($user_id)) {
    $user_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;
    echo ''.$user_id.'';
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
    if ($link) {
     
        $query = "SELECT * FROM cart_details WHERE user_id = '$user_id'";
        $result = mysqli_query($link, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_name = htmlspecialchars($row['product_name']);
                $product_price = htmlspecialchars($row['price']);
                $product_description = htmlspecialchars($row['description']);
                $image_path = htmlspecialchars($row['image_path']);
                $quantity = htmlspecialchars($row['quantity']);
                $total_price = $product_price * $quantity; // Calculate total price based on quantity
                $product_id= $row['product_id'];
                // Display cart details for each product
                echo "
                    <div class='container mt-4'>
                        <div class='row align-items-center mb-4'>
                            <!-- Image Section -->
                            <div class='col-md-3'>
                                <img src='/E-commerce website/admin/$image_path' class='img-fluid' alt='$product_name' style='max-height: 200px; object-fit: cover; border-radius: 10px;'>
                            </div>
                            <!-- Details Section -->
                            <div class='col-md-9'>
                                <h4>$product_name</h4>
                                <p><strong>Price:</strong> ₹$product_price</p>
                                <p><strong>Description:</strong> $product_description</p>
                                <p><strong>Quantity:</strong> $quantity</p>
                                <p><strong>Total Price:</strong> ₹$total_price</p>
                             <a href='/E-commerce website/templates/delete_product.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class='btn btn-danger'>Remove from Cart</a>




                            </div>
                        </div>
                    </div>
                ";
            }
        } else {
            echo "<p>No products in your cart.</p>";
        }
    }
    mysqli_close($link);
} else {
    echo "Error while fetching the cart records";
}

?>
