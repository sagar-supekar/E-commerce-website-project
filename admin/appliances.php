<?php
    include("/home/web/public_html/E-commerce website/includes/header.php");
    include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>

<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
    
    }
    /* #content{
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
     
    } */
     #buy-now{
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
</style>

<?php
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
$results_per_page = 6;  
$query = "SELECT * FROM e_product_details where category='appliances'";
$result = mysqli_query($link, $query);
$number_of_result = mysqli_num_rows($result);  


$number_of_page = ceil ($number_of_result / $results_per_page); 

 
    if (!isset ($_GET['page']) ) {  
        $page = 1;  
    } else {  
        $page = $_GET['page'];  
    }  

        
       $page_first_result = ($page-1) * $results_per_page;  

        
        $query = "SELECT * FROM e_product_details WHERE category='appliances' LIMIT " . $page_first_result . ',' . $results_per_page;

        $result = mysqli_query($link, $query);  

if ($result) {
    echo "<div class='container mt-4'>
            <div class='row'>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        if($row['category']=='appliances')
        {
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);
        
        echo "
        <div class='col-md-4 mb-4' id='content'>
            <div class='card' style='width: 100%;'>
                <img class='card-img-top' src='/E-commerce website/admin//$image_path' alt='$product_name' style='height: 350px; object-fit: cover;'>
                <div class='card-body'>
                    <h5 class='card-title'>$product_name</h5>
                    <p class='card-text'><p style='font-weight:bold'>Price: $product_price</p></p>
                    <p class='card-text'><h6>Description:</h6> $product_description</p>
                    <div class='row'>
                        <div class='col md-6'>
                            <a href='#' class='btn btn-buy-now' id=buy-now style='width: 100%;'>Buy Now</a>
                        </div>
                        <div class='col md-6'>
                            <a href='#' class='btn btn-warning' style='width: 100%;'>Add To Cart</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        ";
         }
    }
    
    echo "  </div>
        </div>";
} else {
    echo "Error fetching products: " . mysqli_error($link);
}

mysqli_close($link);

//pagination logic


// if page =1 
echo "<div class='pagination'>";
if ($page > 1) {
    echo '<a href="welcome.php?page=' . ($page - 1) . '">Previous</a>';
} else {
    echo '<a href="#" class="disabled">Previous</a>';
}

//highlight the current page 

for ($i = 1; $i <= $number_of_page; $i++) {
    if ($i == $page) {
        // Highlight the active page
        echo '<a href="welcome.php?page=' . $i . '" class="active">' . $i . '</a>';
    } else {
        // Regular page number link
        echo '<a href="welcome.php?page=' . $i . '">' . $i . '</a>';
    }
}

// if page= $number_of_pages (reach to the last row)

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
