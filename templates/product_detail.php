<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

if (isset($_GET["product_id"])) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $product_id = $_GET["product_id"];
    $query = "SELECT * FROM e_product_details WHERE product_id='$product_id'";  
    $result = mysqli_query($link, $query);  
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);

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
                    <div class='row mt-2'>
                <div class='col-6'>
                    <a href='" . (isset($login_id) && !empty($login_id) 
                                    ? '/E-commerce website/templates/buy_now.php' 
                                    : '/E-commerce website/templates/login.php') . "' 
                       class='btn btn-buy-now' 
                       id='buy-now' 
                       style='width: 100%; background-color: #007bff; color: white; font-weight: bold;'>
                       Buy Now
                    </a>
                </div>
                <div class='col-6'>
                    <a href='".(isset($login_id) && !empty($login_id) 
                    ? '/E-commerce website/templates/add_to_cart.php?product_id='. $product_id.'&user_id='.$login_id
                    : '/E-commerce website/templates/login.php') ."' 
                       class='btn btn-warning' 
                       style='width: 100%; font-weight: bold;'>
                       Add To Cart
                    </a>
                </div>
            </div>
                </div>
            </div>
        </div>
        ";
    } else {
        echo "<div class='text-center mt-5'><h3>No product found!</h3></div>";
    }

    mysqli_close($link);
} else {
    echo "<div class='text-center mt-5'><h3>Error: No product selected!</h3></div>";
}
?>
