<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>

<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
    }

    #buy-now {
        background-color: #fd7e14;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
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

    .pagination .disabled {
        background-color: #ccc;
        color: #999;
        pointer-events: none;
    }

    .pagination .active {
        background-color: red;
        color: white;
    }
    #image-card
    {
        height:300px;
        /* max-height: 300px; max-width: 100%; object-fit: cover; border-radius: 5px; */
    }
</style>
<?php
include("carousel.php");
?>
<?php
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$results_per_page = 6;
$query = "SELECT * FROM e_product_details";
$result = mysqli_query($link, $query);
$number_of_result = mysqli_num_rows($result);

$number_of_page = ceil($number_of_result / $results_per_page);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_first_result = ($page - 1) * $results_per_page;

$query = "SELECT * FROM e_product_details LIMIT $page_first_result, $results_per_page";
$result = mysqli_query($link, $query);

// <p class='card-text'><h6>Description:</h6> $product_description</p>
// <p class='card-text' style='color: #666; font-style: italic;'>
// <h6 style='font-weight: bold; font-size: 14px;'>Description:</h6> 
// $product_description
// </p>

if ($result) {
    echo "<div class='container mt-4'>
            <div class='row'>";

    while ($row = mysqli_fetch_assoc($result)) {
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);
        $quantity= $row['quantity'];
        $login_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;
        $product_id = $row['product_id'];
        if($quantity==0)
        {
            //echo "product is out of stock";
            echo "
<div class='col-md-4 mb-4'>
    <div class='card' style='width: 90%; max-height: 450px; margin: auto; position: relative;'>
        <img class='card-img-top' id='image-card' 
             src='/E-commerce website/admin/$image_path' 
             alt='$product_name' style='opacity: 0.5; filter: blur(2px);'>
             
        <div class='watermark' style='
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18px;
            color: red;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 4px;
            text-transform: uppercase;'>
            Out of Stock
        </div>
        
        <div class='card-body' style='font-family: Roboto, sans-serif; font-size: 14px; opacity: 0.5; pointer-events: none;'>
            <a href='/E-commerce website/templates/product_detail.php?product_id=$product_id' 
               style='text-decoration:none; color: inherit; font-weight: bold;'>
                <p class='card-text'>$product_name</p>
            </a>
            <p class='card-text' style='font-weight: bold; color: #444;'>Price: ₹ $product_price</p>
            
            <div class='row mt-2'>
                <div class='col-6'>
                    <a href='' 
                       class='btn btn-buy-now' 
                       id='buy-now' 
                       style='width: 100%; background-color: #007bff; color: white; font-weight: bold; cursor: not-allowed;'>
                       Buy Now
                    </a>
                </div>
                <div class='col-6'>
                    <a href='' 
                       class='btn btn-warning' 
                       style='width: 100%; font-weight: bold; cursor: not-allowed;'>
                       Add To Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
";

        }
        else
        {
        echo "
        <div class='col-md-4 mb-4'>
         <div class='card' style='width: 90%; max-height: 450px; margin: auto;'>
        <img class='card-img-top' id='image-card' 
             src='/E-commerce website/admin/$image_path' 
             alt='$product_name'>
        <div class='card-body' style='font-family: 'Roboto', sans-serif; font-size: 14px;'>
            <a href='/E-commerce website/templates/product_detail.php?product_id=$product_id' 
        style='text-decoration:none; color: inherit; font-weight: bold;'>
        <p class='card-text'>$product_name</p>
        </a>

            <p class='card-text' style='font-weight: bold; color: #444;'>Price:  ₹ $product_price</p>
           
            <div class='row mt-2'>
                <div class='col-6'>
                    <a href='" . (isset($login_id) && !empty($login_id) 
                                    ? '/E-commerce website/templates/buy_now.php?product_id='. $product_id.'&user_id='.$login_id
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

        ";}
    }
    echo "  </div>
        </div>";
} else {
    echo "Error fetching products: " . mysqli_error($link);
}

mysqli_close($link);

echo "<div class='pagination'>";
//whwn page=1
if ($page > 1) {
    echo '<a href="welcome.php?page=' . ($page - 1) . '">Previous</a>';
} else {
    echo '<a href="#" class="disabled">Previous</a>';
}

//highlight the active page
for ($i = 1; $i <= $number_of_page; $i++) {
    if ($i == $page) {
        echo '<a href="welcome.php?page=' . $i . '" class="active">' . $i . '</a>';
    } else {
        echo '<a href="welcome.php?page=' . $i . '">' . $i . '</a>';
    }
}

//if current page is less than page size add one more page 
if ($page < $number_of_page) {
    echo '<a href="welcome.php?page=' . ($page + 1) . '">Next</a>';
} else {
    echo '<a href="#" class="disabled">Next</a>';
}
echo "</div>";

?>

<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>