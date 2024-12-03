<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";
echo "<div class='d-flex justify-content-center'><h1>Your Cart Items</h1></div>";
if (isset($user_id)) {
    $user_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;
  
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
                               <div class='row'>
                                    <div class='col-sm'>
                                        <a href='/E-commerce website/templates/delete_product.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class='btn btn-danger style='margin-right: 0;'>Remove from Cart</a>
                                        <a href='/E-commerce website/templates/buy_now.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class='btn btn-warning style='margin-right: 0;'>Buy Now</a>
                                    </div>
                                </div>
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
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>