<?php
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
//remove quantity part


//remove headers navbars and other part back button which redirect to home


if (isset($_GET["product_id"]) && isset($_GET["user_id"])) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $product_id = mysqli_real_escape_string($link, $_GET["product_id"]);
    $user_id = mysqli_real_escape_string($link, $_GET["user_id"]);

    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);

        // Check if the product is already in the cart
        $check_query = "
            SELECT * FROM cart_details 
            WHERE user_id = '$user_id' AND product_id = '$product_id'
        ";
        $check_result = mysqli_query($link, $check_query);
        //if item is alredy in cart then show message and redirect it to welcome page with message 
        if(mysqli_num_rows($check_result) > 0) {
            $row = mysqli_fetch_assoc($check_result);
            $cart_id= $row["cart_id"];
            header("Location:show_cart_items.php?user_id=$user_id");
        }
        if (mysqli_num_rows($check_result) == 0) {
            // Insert into `cart_details` table if not already present
            $cart_query = "
                INSERT INTO cart_details (
                    user_id, product_id, product_name, price, description, category, image_path, quantity
                ) VALUES (
                    '$user_id', '$product_id', '$product_name', '$product_price', '$product_description', '$category', '$image_path', 1
                )";
            
            $cart_result = mysqli_query($link, $cart_query);
            if($cart_result)
            {
                header("Location:show_cart_items.php?user_id=$user_id");
            }
        }
        //remove echo part which is showing the cart particular cart item
        
    } else {
        echo "<p style='color: red;'>Product not found!</p>";
    }

    mysqli_close($link);
} else {
    echo "<p style='color: red;'>Invalid request! Product ID or User ID missing.</p>";
}
//function for generating the cart count

?>


<!-- script for the show cart count without reloading the page -->
