<?php
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
    if (!$link) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['product_id']) && isset($input['user_id']) && isset($input['quantity'])) {
        $product_id = mysqli_real_escape_string($link, $input['product_id']);
        $user_id = mysqli_real_escape_string($link, $input['user_id']);
        $quantity = (int) $input['quantity'];

        // Update quantity and calculate new price
        $query = "
            UPDATE cart_details 
            SET quantity = '$quantity', 
                price = (SELECT price FROM e_product_details WHERE product_id = '$product_id') * '$quantity'
            WHERE user_id = '$user_id' AND product_id = '$product_id'
        ";

        if (mysqli_query($link, $query)) {
            $new_price_query = "
                SELECT price FROM e_product_details WHERE product_id = '$product_id'
            ";
            $price_result = mysqli_query($link, $new_price_query);
            $price_row = mysqli_fetch_assoc($price_result);
            $updated_price = $price_row['price'] * $quantity;
            $new_quantity_query = "
                SELECT quantity FROM cart_details WHERE user_id = '$user_id' AND product_id = '$product_id'
            ";
            $quantity_result = mysqli_query($link, $new_quantity_query);
            $quantity_row = mysqli_fetch_assoc($quantity_result);
            $updated_quantity = $quantity_row['quantity'];

            echo json_encode([
                'success' => true,
                'updated_price' => $updated_price,
                'updated_quantity' => $updated_quantity
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart details']);
        }
        mysqli_close($link);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
}

include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";
echo "<div class='d-flex justify-content-center'><h1>Your Cart Items</h1></div>";
if (isset($user_id)) {
    $user_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;
  
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
    if ($link) {
     
        $query = "SELECT * FROM cart_details WHERE user_id = '$user_id' ORDER BY updated_at DESC";

        $result = mysqli_query($link, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_name = htmlspecialchars($row['product_name']);
                $product_price = htmlspecialchars($row['price']);
                $product_description = htmlspecialchars($row['description']);
                $image_path = htmlspecialchars($row['image_path']);
                $quantity = htmlspecialchars($row['quantity']);
                $total_price = $product_price * $quantity; // Calculate total price based on quantity
                $product_id = $row['product_id'];
                // Display cart details for each product
                echo "
                    <div class='container mt-4  id='$product_id'>
                        <div class='row align-items-center mb-4'>
                            <div class='col-md-3'>
                                <img src='/E-commerce website/admin/$image_path' class='img-fluid' alt='$product_name' style='max-height: 200px; object-fit: cover; border-radius: 10px;'>
                            </div>
                            <div class='col-md-9 my-2''>
                                <h4>$product_name</h4>
                                <p><strong>Price:</strong> ₹<span class='product-price' id='price-$product_id'>$product_price</span></p>
                                <p><strong>Quantity:</strong><span id='q-quantity-$product_id'> $quantity</span></p>
                                <p><strong>Total Price: </strong><span id='total-price-$product_id'>₹$total_price</span></p>
                                 <div class='d-flex row'>
                                     
                                    <label for='quantity-$product_id' class='form-label'><strong>Quantity:</strong></label>
                                    <div class='col-3'>
                                     <select id='quantity-$product_id' class='form-select' style='width: 120px;'>
                                     " . generateQuantityOptions($quantity) . "
                                     </select>
                                      </div>
                                    <div class='col-3'>
                                        <a href='/E-commerce website/templates/delete_product.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class='btn btn-danger style='margin-right: 0;'>Remove from Cart</a>
                                    </div>
                                </div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                ";
            }
            echo "<hr>";
                echo "<div style='display: flex; justify-content:end; align-items: center;margin-right:100px;margin-bottom:100px;'>
                <a href='/E-commerce website/templates/buy_cart_items.php?user_id=" . urlencode($user_id) ."' 
                style='padding: 10px; background-color: blue; color: white; text-align:end; text-decoration: none; 
                border: 2px solid #e0a800; border-radius: 5px; font-size: 16px; font-weight: bold; '>
                Check Out</a>
                </div>";
               
        } else {
            echo "<div class='alert alert-danger m-auto my-2 d-flex justify-content-center w-50'>No Items in your cart</div>";
        }
    }
    mysqli_close($link);
} else {
    echo "Error while fetching the cart records";
}
function generateQuantityOptions($selectedQuantity) {
    $options = "";
    for ($i = 1; $i <= 10; $i++) {
        $selected = ($i == $selectedQuantity) ? 'selected' : '';
        $options .= "<option value='$i' $selected>$i</option>";
    }
    return $options;
}
//$product_ids_str = implode(',', $product_ids);
//echo"<a href='/E-commerce website/templates/buy_now.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class=d-flex justify-content-end'>Buy Now</a>";

?>







<script>
    document.addEventListener("DOMContentLoaded", function () {
    const quantityDropdowns = document.querySelectorAll("[id^='quantity-']");
    quantityDropdowns.forEach((dropdown) => {
        dropdown.addEventListener("change", function () {
            const selectedQuantity = this.value;
            const productId = this.id.split('-')[1];

            //const priceElement = document.getElementById(`price-${productId}`);
            const totalPriceElement = document.getElementById(`total-price-${productId}`);
            const quantityElement = document.getElementById(`q-quantity-${productId}`);
            
            const userId = "<?php echo $user_id; ?>"; // User ID from PHP

            // AJAX request to update quantity and price
            fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    product_id: productId,
                    user_id: userId,
                    quantity: selectedQuantity,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    
                    totalPriceElement.textContent =data.updated_price;
                    quantityElement.textContent =data.updated_quantity; 
                    //priceElement.textContent =data.updated_price / data.updated_quantity;
                } else {
                    alert("Failed to update quantity!");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while updating the quantity.");
            });
        });
    });
});


</script>
<html>
    <head>
        <title>Cart Items</title>
    </head>
</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>