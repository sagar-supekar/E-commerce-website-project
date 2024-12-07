<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>
 <?php
 echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
 echo "
 <div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
     <a href='/E-commerce website/templates/category/appliencs_cart_templates.php' class='text-decoration-none'>
         <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
     </a>
 </div>";
 
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
        margin-top: 30px;
    }

    .product-item {
        width: 32%; /* Each product takes up one-third of the row */
        display: flex;
        align-items: center;
        background-color: #f9f9f9;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        margin-bottom: 20px; /* Adds space between each product */
        position: relative;
    }

    .product-image {
        width: 150px; /* Adjusted width */
        height: 150px; /* Adjusted height */
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

    #buy-now{
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
        height: 1px;
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
/* Product image watermark for out-of-stock items */

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
    pointer-events: none; /* Disable buttons */
}

.product-image.out-of-stock {
    filter: grayscale(100%);
    opacity: 0.8;
}


</style>

<?php

echo"<div class='d-flex justify-content-center my-2' ><h3>WASHING MACHINE</h3></div>";
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$results_per_page = 6;
$query = "SELECT * FROM e_product_details where subcategory='washing_machine'";
$result = mysqli_query($link, $query);
$number_of_result = mysqli_num_rows($result);

$number_of_page = ceil($number_of_result / $results_per_page);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_first_result = ($page - 1) * $results_per_page;

$query = "SELECT * FROM e_product_details WHERE subcategory='washing_machine' LIMIT $page_first_result, $results_per_page";
$result = mysqli_query($link, $query);

if ($result) {
    echo "<div class='product-container'>";

    while ($row = mysqli_fetch_assoc($result)) {
      //  echo  $row["subcategory"]."  ";
        if($row['subcategory']=='washing_machine')
        {
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);
        $quantity = $row['quantity'];
        $login_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : null;
        $product_id = $row['product_id'];

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
                        <a href='/E-commerce website/templates/product_detail.php?product_id=$product_id' style='text-decoration:none'>
                            <p class='product-name'>$product_name</p> 
                        </a>
                        <p class='product-price'>₹ $product_price</p>
                        <div class='product-actions' id='product-button'>
                            <a href='" . (isset($login_id) && !empty($login_id) 
                                ? '/E-commerce website/templates/buy_now.php?product_id='. $product_id.'&user_id='.$login_id
                                : '/E-commerce website/templates/login.php') . "' 
                                class='btn btn-buy-now' id='buy-now'>
                                Buy Now
                            </a>
                            <a href='" . (isset($login_id) && !empty($login_id) 
                                ? '/E-commerce website/templates/add_to_cart.php?product_id='. $product_id.'&user_id='.$login_id
                                : '/E-commerce website/templates/login.php') . "' 
                                class='btn btn-warning'>
                                 To Cart
                            </a>
                        </div>
                    </div>
                </div>
            ";
        }
    }
    else
    {
       // echo "problem while fetching the items where category is keypad";
    }
    }

    echo "</div>";
} else {
    echo "Error fetching products: " . mysqli_error($link);
}

mysqli_close($link);

echo "<div class='pagination'>";
// When page = 1
if ($page > 1) {
    echo '<a href="washing_machine.php?page=' . ($page - 1) . '">Previous</a>';
} else {
    echo '<a href="#" class="disabled">Previous</a>';
}

// Highlight the active page
for ($i = 1; $i <= $number_of_page; $i++) {
    if ($i == $page) {
        echo '<a href="washing_machine.php?page=' . $i . '" class="active">' . $i . '</a>';
    } else {
        echo '<a href="washing_machine.php?page=' . $i . '">' . $i . '</a>';
    }
}

// If current page is less than page size, add one more page
if ($page < $number_of_page) {
    echo '<a href="washing_machine.php?page=' . ($page + 1) . '">Next</a>';
} else {
    echo '<a href="#" class="disabled">Next</a>';
}
echo "</div>";

?>
<html>
    <head>
        <title>Washing Machine</title>
    </head>
</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>
