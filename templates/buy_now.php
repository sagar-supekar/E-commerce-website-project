<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include headers
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";

$nameErr = $addressErr = $pincodeErr = $mobileErr = $quantityErr = $paymentMErr = '';
$product = null;

// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $errors = [];
    $user_id = $_SESSION['login_id'] ?? null;
    $product_id = $_GET['product_id'] ?? null;

    if (!$product_id || !$user_id) {
        die("Invalid request: Missing product_id or user_id.");
    }

    // Fetch product details
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $image_path = htmlspecialchars($row['image_path']);
        $current_quantity = $row['quantity']; // Get the current stock quantity
    }

    // Form input
    $full_name = trim($_POST["full_name"]);
    $address = trim($_POST["address"]);
    $pincode = trim($_POST["pincode"]);
    $mobile = trim($_POST["mobile"]);
    $quantity = trim($_POST["quantity"]);
    $payment_method = $_POST["payment_method"] ?? "";

    // Input validation
    if (empty($full_name)) {
        $nameErr = "Full Name is required.";
        $errors[] = $nameErr;
    }
    if (empty($address)) {
        $addressErr = "Address is required.";
        $errors[] = $addressErr;
    }
    if (empty($pincode) || !preg_match("/^\d{6}$/", $pincode)) {
        $pincodeErr = "Invalid Pincode. Must be 6 digits.";
        $errors[] = $pincodeErr;
    }
    if (empty($mobile) || !preg_match("/^\d{10}$/", $mobile)) {
        $mobileErr = "Invalid Mobile Number. Must be 10 digits.";
        $errors[] = $mobileErr;
    }
    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0 || $quantity > 5) {
        $quantityErr = "Quantity must be a positive number and not greater than 5.";
        $errors[] = $quantityErr;
    }
    if (empty($payment_method)) {
        $paymentMErr = "Please select a Payment Method.";
        $errors[] = $paymentMErr;
    }

    // Check if the requested quantity is available
    if ($quantity > $current_quantity) {
        $quantityErr = "Insufficient stock. Only $current_quantity items available.";
        $errors[] = $quantityErr;
    }

    if (empty($errors)) {
        // Insert order into database
        $query = "
            INSERT INTO order_details (user_id, product_id, product_name, product_price, full_name, address, pincode, mobile_number, quantity, payment_method, image_path)
            VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$full_name', '$address', '$pincode', '$mobile', $quantity, '$payment_method', '$image_path')
        ";
        if (mysqli_query($link, $query)) {
            echo "<p style='color: green; text-align: center;'>Order placed successfully!</p>";
            
            // Update the product quantity after placing the order
            $update_query = "UPDATE e_product_details SET quantity = quantity - $quantity WHERE product_id = '$product_id'";
            mysqli_query($link, $update_query);
            
            // To check if the stock is less than zero, set it to zero
            if ($current_quantity - $quantity <= 0) {
                $update_query = "UPDATE e_product_details SET quantity = 0 WHERE product_id = '$product_id'";
                mysqli_query($link, $update_query);
            }
            //check if given product is available in the cart execute the delete query to remove it 
        } else {
            echo "<p style='color: red; text-align: center;'>Error placing order: " . mysqli_error($link) . "</p>";
        }
    }
}

// Fetch product for display
if (isset($_GET["product_id"])) {
    $product_id = mysqli_real_escape_string($link, $_GET["product_id"]);
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<p style='color: red; text-align: center;'>Product not found.</p>";
    }
}

mysqli_close($link);
ob_end_flush();
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Buy Product</h2>

    <?php if ($product): ?>
        <div class="row">
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

            <div class="col-md-6">
                <div class="card p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($full_name) ? $full_name : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $nameErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($address) ? $address : ''; ?></textarea>
                            <span class="error" style="color:red;"><?php echo $addressErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo isset($pincode) ? $pincode : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $pincodeErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo isset($mobile) ? $mobile : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $mobileErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo isset($quantity) ? $quantity : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $quantityErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div>
                                <input type="radio" id="cod" name="payment_method" value="COD" <?php echo (isset($payment_method) && $payment_method == 'COD') ? 'checked' : ''; ?>>
                                <label for="cod">Cash on Delivery</label><br>
                                <input type="radio" id="online" name="payment_method" value="Online" <?php echo (isset($payment_method) && $payment_method == 'Online') ? 'checked' : ''; ?>>
                                <label for="online">Online Payment</label><br>
                            </div>
                            <span class="error" style="color:red;"><?php echo $paymentMErr; ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>