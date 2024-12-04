<?php
ob_start();
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

if (isset($_GET['user_id']) && isset($_GET['product_id'])) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

 
    $product_id = mysqli_real_escape_string($link, $_GET['product_id']);
    $user_id = mysqli_real_escape_string($link, $_GET['user_id']);

    $check_query = "SELECT * FROM order_details WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($link, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        
        $delete_query = "DELETE FROM order_details WHERE product_id = '$product_id' AND user_id = '$user_id'";

        if (mysqli_query($link, $delete_query)) {
           
            if (isset($_GET['remark'])) {
                
                header("Location: /E-commerce website/templates/order_history.php?remark=delete_item_successfully");
            } else {
               
                header("Location: /E-commerce website/templates/show_cart_items.php");
            }
            exit(); 
        } else {
            echo "<p style='color: red;'>Error while deleting item: " . mysqli_error($link) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>No matching product found to delete.</p>";
    }

    mysqli_close($link);
} else {
    // Missing user_id or product_id in URL
    echo "<p style='color: red;'>Invalid request! Missing product_id or user_id.</p>";
}

ob_end_flush();
?>
