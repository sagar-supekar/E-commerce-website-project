<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>

<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
    }

    .product-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
        margin: 30px;
        background-color: #f8f8fb;
        padding:10px;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
    }

    .product-item {
        width: 32%;
        display: flex;
        align-items: center;
        background-color: #f9f9f9;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
    }

    .product-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 20px;
    }

    .product-details {
        flex-grow: 1;
    }

    .product-name {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 16px;
        font-weight: bold;
        color: #444;
        margin-bottom: 15px;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    #product-button {
        width: 144%;
        font-weight: bold;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    #buy-now {
        background-color: #007bff;
        color: white;
    }

    .btn-warning {
        background-color: #f0ad4e;
        color: white;
    }

    .disabled {
        /* background-color: #ccc; */
        color: #999;
        pointer-events: none;
    }

    .product-item::after {
        content: "";
        display: block;
        width: 100%;
        height: 0px;
        background-color: #ddd;
        margin-top: 20px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        list-style-type: none;
        padding: 0;
        margin: 20px 0;
    }

    .pagination a {
        text-decoration: none;
        margin: 0 5px;
        padding: 8px 16px;
        background-color: #fd7e14;
        color: white;
        border-radius: 4px;
        font-size: 16px;
    }

    .pagination a:hover {
        background-color: #e56a03;
    }

    .pagination .active {
        background-color: red;
        color: white;
    }

    .pagination .disabled {

        color: #7f7f7f;
        pointer-events: none;
        cursor: not-allowed;
    }

    .pagination .disabled:hover {
        /* background-color: #d3d3d3;  */
        color: #7f7f7f;
    }


    .product-item.out-of-stock::after {
        content: "Out of Stock";
        position: absolute;
        top: 60%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        font-weight: bold;
        color: red;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 10px 20px;
        /* border-radius: 5px; */
        z-index: 10;
        text-align: center;
        /* white-space: nowrap; */
    }

    .product-actions.disabled {
        opacity: 0.6;
        pointer-events: none;
    }

    .product-image.out-of-stock {
        filter: grayscale(100%);
        opacity: 0.8;
    }
   #cart__item{
    color:blue;
   }
</style>
<?php

?>
<?php
$product_name=$_GET['cart_message'];
echo "<div class='text-center' id='cart__item'> $product_name";
include("carousel.php");

//echo "<img src='/E-commerce website/admin/uploads/e-commerce.jpeg' style='height:40%;width:100%;padding:20px;'>";
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT * FROM e_product_details ORDER BY product_id desc LIMIT 6";
$result = mysqli_query($link, $query);
//echo "<div class='d-flex justify-content-center my-2' style=color:green;background-color:yellow;width:500px;margin-left:500px;height:30px;padding:5px;border-radius:10px;>$cart_message</div>";
if ($result) {
    echo "<div class='text-center my-2'><h3 style='margin-top:15px;font-family:Helvetica Neue', Helvetica, Arial, sans-serif';background-color:#f8f8fb;width:100%;>Recently Added</h3></div>";
    echo "<div class='product-container'>";
   
    while ($row = mysqli_fetch_assoc($result)) {
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);
        $quantity = $row['quantity'];
        $login_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : null;
        $product_id = $row['product_id'];
        //$cart_message=($product_id==$cart_id)?$_GET['cart_message']:'';

        if ($quantity == 0) {
            echo "
                <div class='product-item out-of-stock'>
                    <img class='product-image out-of-stock' src='/E-commerce website/admin/$image_path' alt='$product_name'>
                    <div class='product-details'>
                        
                            <p class='product-name'>$product_name</p>
                        
                        <p class='product-price'>₹ $product_price</p>
                    </div>
                </div>
            ";
        } else {
            echo "
    <div class='product-item'>
        <img class='product-image' src='/E-commerce website/admin/$image_path' alt='$product_name'>
        <div class='product-details'>
            <a href='/E-commerce website/templates/product_detail.php?product_id=$product_id&user_id=$login_id' style='text-decoration:none'>
               
            <p class='product-name'>$product_name</p> 
            </a>
            <p class='product-price'>₹ $product_price</p>
            <div class='product-actions' id='product-button'>
                <a href='" . (isset($login_id) && !empty($login_id)
                ? '/E-commerce website/templates/buy_now.php?product_id=' . $product_id . '&user_id=' . $login_id
                : '/E-commerce website/templates/login.php') . "' 
                    class='btn btn-buy-now' id='buy-now'>
                    Buy Now
                </a>
                <a href='" . (isset($login_id) && !empty($login_id)
                ? '/E-commerce website/templates/add_to_cart.php?product_id=' . $product_id . '&user_id=' . $login_id
                : '/E-commerce website/templates/login.php') . "' 
                    class='btn btn-warning' onclick='addToCart($product_id); return false;'>
                    To Cart
                </a>
            </div>
        </div>
    </div>
     
";
        }
    }

    echo "</div>";
} else {
    echo "Error fetching products: " . mysqli_error($link);
}

mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EzyBuy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
        }

        .card {
            width: 18rem;
            /* Set a consistent width for all cards */
            height: 25rem;
            /* Set a consistent height for all cards */
            margin: 0 auto;
            /* Center align each card horizontally */
        }

        .card-img-top {
            height: 197px;
            /* Set a consistent height for all images */
            width: 110%;
            /* Make the image take the full width of the card */
            object-fit: cover;
            /* Ensure images cover the area proportionally without distortion */
        }

        .con {
            display: flex;
            justify-content: center;
            padding-bottom: 100px;
            

        }

        .ram {
            border: 1px;
            background-color: #f8f8fb;
            margin:30px;
            /* padding-top: 70px; */
        }

        .rw {
            justify-content: center;
            /* Align cards in the center of the row */
        }
    </style>
</head>

<body>

    <div class="ram">
        <h3 class="text-center mt-4 mx-4" style=" font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;padding-top:15px;">Categories</h3>
        <div class="container con my-5 mb-5">

            <div class="row rw">
                <!-- Card 1 -->
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card">
                        <img class="card-img-top p-2" src="/E-commerce website/admin/uploads/mobiles.jpeg" alt="Laptop">
                        <div class="card-body">
                            <!-- <h5 class="text-center">Price: ₹ 80,000</h5> -->
                            <a href="/E-commerce website/templates/category/mobile_cart_templates.php" class="btn btn-primary w-100">Mobile</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card">
                        <img class="card-img-top p-4" src="/E-commerce website/admin/uploads/electronics.jpeg" alt="Smart Watches">
                        <div class="card-body">
                            <!-- <h5 class="text-center">Price: ₹ 4,000</h5> -->
                            <a href="/E-commerce website/templates/category/electronics_cart_templates.php" class="btn btn-primary w-100">Electronics</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card">
                        <img class="card-img-top p-4" src="/E-commerce website/admin/uploads/appliences.jpeg" alt="Earbuds">
                        <div class="card-body">
                            <!-- <h5 class="text-center">Price: ₹ 2,000</h5> -->
                            <a href="/E-commerce website/templates/category/appliencs_cart_templates.php" class="btn btn-primary w-100">Appliances</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>
<!-- script for cart count -->