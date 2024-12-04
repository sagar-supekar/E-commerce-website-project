<?php
ob_start();
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

if (isset($_GET['user_id'])) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $product_id = mysqli_real_escape_string($link, $_GET['product_id']);
    $user_id = mysqli_real_escape_string($link, $_GET['user_id']);


    // Delete the item from the cart
    $delete_query = "DELETE FROM cart_details WHERE product_id = '$product_id' AND user_id = '$user_id'";

    if (mysqli_query($link, $delete_query)) {
        if (isset($_GET['remark'])) {
            header("Location: /E-commerce website/templates/order_history.php?remark='delete item successfuly'");
        } else {
            header("Location: /E-commerce website/templates/show_cart_items.php");
        }
        exit(); 
    } else {
        echo "<p style='color: red;'>Error while deleting item: " . mysqli_error($link) . "</p>";
    }

    mysqli_close($link);
} else {
    echo "<p style='color: red;'>Invalid request!</p>";
}
ob_end_flush();
?>
