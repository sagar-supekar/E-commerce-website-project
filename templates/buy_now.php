<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Redirect if not logged in
if (!isset($_COOKIE['login_id'])) {
    header("Location:welcome.php");
    exit();
}

// Include headers
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

// Add CSS for Font Awesome
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";

// Initialize variables
$nameErr = $emailErr = $addressErr = $pincodeErr = $mobileErr = $quantityErr = $paymentMErr = '';
$product = null;
$user_id = $_COOKIE['login_id'] ?? null;

// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch product details
if (isset($_GET['product_id'])) {
    $product_id = mysqli_real_escape_string($link, $_GET['product_id']);
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<p style='color: red; text-align: center;'>Product not found.</p>";
    }
}

// Check for address_id
$address_query = "SELECT * FROM address WHERE user_id = '$user_id'";
$address_result = mysqli_query($link, $address_query);
$address = mysqli_fetch_assoc($address_result);
$address_available = !empty($address);

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $buyerName=$address['name'];
    $errors = [];
    $quantity = trim($_POST["quantity"]);
    $payment_method = $_POST["payment_method"] ?? "";

    // Validation
    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0 || $quantity > 5) {
        $quantityErr = "Quantity must be a positive number and not greater than 5.";
        $errors[] = $quantityErr;
    }
    if (empty($payment_method)) {
        $paymentMErr = "Please select a Payment Method.";
        $errors[] = $paymentMErr;
    }

    // Check stock availability
    $current_quantity = $product['quantity'];
    if ($quantity > $current_quantity) {
        $quantityErr = "Insufficient stock. Only $current_quantity items available.";
        $errors[] = $quantityErr;
    }

    // Process order if no errors
    if (empty($errors)) {
        $product_name = htmlspecialchars($product['product_name']);
        $product_price = htmlspecialchars($product['price']);
        $image_path = htmlspecialchars($product['image_path']);
        $buyerName=$address['name'];
        $mobileNumber=$address['mobile_no'];
        $user_address=$address['address'];
        $pincode=$address['pincode'];
        // Insert order
        $order_query = "
            INSERT INTO order_details (user_id, product_id,full_name, product_name, product_price, quantity, payment_method, image_path,mobile_number,address,pincode)
            VALUES ('$user_id', '$product_id','$buyerName','$product_name', '$product_price', '$quantity', '$payment_method', '$image_path',' $mobileNumber','$user_address',$pincode)
        ";
        if (mysqli_query($link, $order_query)) {
            // Update stock
            $update_query = "UPDATE e_product_details SET quantity = quantity - $quantity WHERE product_id = '$product_id'";
            mysqli_query($link, $update_query);
        
            // Prepare data for the email
            $buyerName = urlencode($address['name']);
            $price = urlencode($product['price'] * $quantity); // Total price
            $paymentMethod = urlencode($payment_method);
            $placedAddress = urlencode($address['address']);
            $quantity = urlencode($quantity);
            $email = urlencode($address['email']);
            $product_name = urlencode($product['product_name']);
        
            $email_url = "/E-commerce website/templates/send_email.php?". "buyerName=$buyerName". "&price=$price". "&paymentMethod=$paymentMethod". "&placedAddress=$placedAddress" . "&quantity=$quantity" . "&email=$email" . "&product_name=$product_name";
        
            // Redirect to send_email.php with parameters
            header("Location: $email_url");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Error placing order: " . mysqli_error($link) . "</p>";
        }
        
    }
}

mysqli_close($link);
ob_end_flush();
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Buy Product</h2>

    <?php if ($product): ?>
        <div class="row">
            <!-- Product Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="/E-commerce website/admin/<?php echo $product['image_path']; ?>" class="img-fluid rounded mb-3" alt="<?php echo $product['product_name']; ?>" style="max-width: 100%;">
                        <h4><?php echo $product['product_name']; ?></h4>
                        <p class="text-muted">Price: ₹<?php echo $product['price']; ?></p>
                        <p><?php echo $product['description']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Address & Order Form -->
            <div class="col-md-6">
                <div class="card p-4">
                    <?php if ($address_available): ?>
                        <h5>Your Address</h5>
                        <p><strong>Name: </strong><?php echo $address['name']; ?></p>
                        <p><strong>Address: </strong><?php echo $address['address']; ?></p>
                        <p><strong>Pincode: </strong><?php echo $address['pincode']; ?></p>
                        <p><strong>Mobil No. :</strong><?php echo $address['mobile_no']; ?></p>
                    <?php else: ?>
                        <?php include("/home/web/public_html/E-commerce website/templates/address.php"); ?>
                    <?php endif; ?>

                    <?php if ($address_available): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="text" class="form-control" id="quantity" name="quantity">
                                <span class="error" style="color:red;"><?php echo $quantityErr; ?></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div>
                                    <input type="radio" id="cod" name="payment_method" value="COD">
                                    <label for="cod">Cash on Delivery</label><br>
                                    <input type="radio" id="online" name="payment_method" value="Online">
                                    <label for="online">Online Payment</label><br>
                                </div>
                                <span class="error" style="color:red;"><?php echo $paymentMErr; ?></span>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Place Order</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>
