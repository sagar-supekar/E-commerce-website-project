<?php
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle AJAX request to update quantity and price
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
                SELECT (SELECT price FROM e_product_details WHERE product_id = '$product_id') * '$quantity' AS updated_price
            ";
            $result = mysqli_query($link, $new_price_query);
            $row = mysqli_fetch_assoc($result);
            //send the updated price by using json encode 
            echo json_encode(['success' => true, 'updated_price' => $row['updated_price']]);
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

// Standard page rendering
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-4 row'>
    <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
    <div class='d-flex justify-content-center'><h1>Cart Item</h1></div>
</div> ";

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

        if (mysqli_num_rows($check_result) == 0) {
            // Insert into `cart_details` table if not already present
            $cart_query = "
                INSERT INTO cart_details (
                    user_id, product_id, product_name, price, description, category, image_path, quantity
                ) VALUES (
                    '$user_id', '$product_id', '$product_name', '$product_price', '$product_description', '$category', '$image_path', 1
                )";
            
            $cart_result = mysqli_query($link, $cart_query);
        }

        echo "
        <div class='container mt-5'>
            <div class='row justify-content-center align-items-center'>
                <!-- Image Section -->
                <div class='col-md-6 text-center'>
                    <img class='img-fluid' src='/E-commerce website/admin/$image_path' alt='$product_name' style='height: 350px; object-fit: cover; border-radius: 10px;'>
                </div>
                <!-- Content Section -->
                <div class='col-md-6'>
                    <h2 class='mb-4'>$product_name</h2>
                    <p><strong>Price:</strong> ₹<span id='price'>$product_price</span></p>
                    <p><strong>Description:</strong></p>
                    <p>$product_description</p>
                    <!-- Quantity Dropdown -->
                    <div class='mb-3'>
                        <label for='quantity' class='form-label'><strong>Quantity:</strong></label>
                        <select id='quantity' class='form-select' style='width: 120px;'>
                            " . generateQuantityOptions() . "
                        </select>
                    </div>
                    <div class='mt-4'>
                        <a href='/E-commerce website/templates/delete_product.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class='btn btn-danger'>Remove from Cart</a>
                        <a href='/E-commerce website/templates/buy_now.php?user_id=" . urlencode($user_id) . "&product_id=" . urlencode($product_id) . "' class='btn btn-warning'>Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
        ";
    } else {
        echo "<p style='color: red;'>Product not found!</p>";
    }

    mysqli_close($link);
} else {
    echo "<p style='color: red;'>Invalid request! Product ID or User ID missing.</p>";
}

function generateQuantityOptions() {
    $options = "";
    for ($i = 1; $i <= 10; $i++) {
        $options .= "<option value='$i'>$i</option>";
    }
    return $options;
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const quantityDropdown = document.getElementById("quantity");
        const priceElement = document.getElementById("price");
        const productId = "<?php echo $product_id; ?>";
        const userId = "<?php echo $user_id; ?>";

        // Event listener for quantity change
        quantityDropdown.addEventListener("change", function () {
            const selectedQuantity = this.value;

            // Make AJAX request to update quantity and price
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
                        // Update the price display
                        priceElement.textContent = data.updated_price;
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
</script>
<!-- script for the show cart count without reloading the page -->
