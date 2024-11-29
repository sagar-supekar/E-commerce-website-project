<?php
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
if ( isset($_GET['user_id'])) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $product_id = mysqli_real_escape_string($link, $_GET['product_id']);
    $user_id = mysqli_real_escape_string($link, $_GET['user_id']);

    // Delete the item from the cart
    $delete_query = "DELETE FROM cart_details WHERE product_id = '$product_id' AND user_id = '$user_id'";

    if (mysqli_query($link, $delete_query)) {
        echo "<p style='color: green;'>Item deleted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error while deleting item: " . mysqli_error($link) . "</p>";
    }

    mysqli_close($link);
} else {
    echo "<p style='color: red;'>Invalid request!</p>";
}
?>
