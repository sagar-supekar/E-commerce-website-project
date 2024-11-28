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
                    <div class='mt-4'>
                        <!-- Quantity Input -->
                        <div class='mb-3'>
                            <label for='quantity' class='form-label'><strong>Quantity:</strong></label>
                            <input type='number' id='quantity' class='form-control' value='1' min='1' style='width: 120px;'>
                        </div>
                        <!-- Buttons -->
                        <div class='d-flex gap-3'>
                            
                            <a href='/E-commerce website/templates/delete_product.php?product_id=$product_id' class='btn btn-danger' style='width: 120px;'>Delete</a>
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
