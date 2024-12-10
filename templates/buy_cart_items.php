<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_COOKIE['login_id'])) {
    header("Location:welcome.php");
    exit();
}
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
    //display products in cart
    echo "
    <div style='display: flex; justify-content: space-between; align-items: flex-start; margin-top: 20px; margin-left:14px;'>
        <!-- Left Section: Cart Items -->
        <div style='width: 60%; border: 1px solid #ccc; border-radius: 8px; padding: 20px;'>
            <h3>Items in Your Cart</h3>
            <table style='width: 100%; border-collapse: collapse;'>
                <thead>
                    <tr>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Image</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Product Name</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Price</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Quantity</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Total</th>
                    </tr>
                </thead>
                <tbody>
    ";
   
    while ($row = mysqli_fetch_assoc($result)) {
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = $row['price'];
        $quantity = $row['quantity'];
        $total_price += $product_price * $quantity;
        $total_items += $quantity;
        $image_path = htmlspecialchars($row['image_path']);
        $row_total = $product_price * $quantity;

        echo "
        <tr>
            <td style='padding: 10px; text-align: center;'>
                <img src='/E-commerce website/admin/$image_path' style='max-width: 80px; height: auto; border-radius: 5px;' alt='$product_name'>
            </td>
            <td style='padding: 10px;'>$product_name</td>
            <td style='padding: 10px;'>₹$product_price</td>
            <td style='padding: 10px;'>$quantity</td>
            <td style='padding: 10px;'>₹$row_total</td>
        </tr>
        ";
    }

    echo "
                </tbody>
            </table>
        </div>

        <!-- Right Section: Price Summary -->
        <div style='width: 35%; text-align: center; padding: 20px; border: 1px solid #ccc; border-radius: 8px; margin-right:25px;'>
            <h2 class='text-center'>Price Details</h2>
            <h4 class='text-start'>Price ($total_items items): ₹" . number_format($total_price, 2) . "</h4>
            <h4 class='text-start'>Delivery Charges :<del>₹120</del><span style='color:green;'> Free </span></h4> 
            <h4 class='text-start'>Total Amount: ₹" . number_format($total_price, 2) . "</h4>
            <div style='display: flex; gap: 20px; margin-top: 20px;'>
                <a href='/E-commerce website/templates/show_cart_items.php?user_id=$user_id' 
                    style='padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                    Cancel
                </a>
                <a href='buy_cart_item_form.php?user_id=$user_id&quantity=$total_items' 
                    style='padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;'>
                    Continoue
                </a>
            </div>
        </div>
    </div>
    ";
} else {
    echo "No products found in your cart.";
}
//echo "address details are"; 


mysqli_close($link);
?>
<html>
    <head>
        <title>EzyBuy-Check-Out</title>
    </head>
</html>
<?php
//include("/home/web/public_html/E-commerce website/includes/footer.php");
?>