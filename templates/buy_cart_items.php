<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_GET['user_id'];
$query = "SELECT * FROM cart_details WHERE user_id = '$user_id'";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $total_price = 0; 
    $total_items = 0; 

    while ($row = mysqli_fetch_assoc($result)) {
        $price = $row['price'];  
        $quantity = $row['quantity']; 
        $total_price += $price * $quantity;  
        $total_items += $quantity; 
    }
    $price_format=number_format($total_price,2);
    echo "
    <div style='display: flex; justify-content: center; align-items: center; margin-top: 50px;'>
        <div style='text-align: center; padding: 20px; border: 1px solid #ccc; border-radius: 8px; width: 400px;'>
            <h3>Price ($total_items items) =₹$price_format</h3>
            <h3>Total amount= ₹ $price_format</h3>
            
            <div style='display: flex; gap:20px; margin-top: 20px;margin-left:18px;'>
                <a href='#?user_id=$user_id' 
                    style='padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                    Cancel
                </a>
                
                <a href='buy_cart_item_form.php?user_id=$user_id&quantity=$total_items' 
                    style='padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                    Buy Now
                </a>
            </div>
        </div>
    </div>
    ";
} else {
    echo "No products found in your cart.";
}

mysqli_close($link);
?>


