<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (!isset($_COOKIE['login_id'])) {
    header("Location:welcome.php");
    exit();
}

$user_id=$_GET['user_id'];
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/buy_cart_items.php?user_id=$user_id' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";

$nameErr = $emailErr = $addressErr = $pincodeErr = $mobileErr = $paymentMErr = $dberror='';
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_GET['user_id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $errors = [];
    $quantity = $_GET['quantity'] ?? 0;
    $dberror = '';

    // Form input
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $pincode = trim($_POST["pincode"]);
    $mobile = trim($_POST["mobile"]);
    $payment_method = $_POST["payment_method"] ?? "";

    // Input validation
    if (empty($full_name)) {
        $nameErr = "Full Name is required.";
        $errors[] = $nameErr;
    }
    if (empty($email)) {
        $emailErr = "Email is required.";
        $errors[] = $emailErr;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
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
    if (empty($payment_method)) {
        $paymentMErr = "Please select a Payment Method.";
        $errors[] = $paymentMErr;
    }

    // If no errors, insert the order
    if (empty($errors)) {
        $cart_item_product_query = "SELECT * FROM cart_details WHERE user_id='$user_id'";
        $cart_item_result = mysqli_query($link, $cart_item_product_query);
        
        if ($cart_item_result && mysqli_num_rows($cart_item_result) > 0) {
            while ($cart_row = mysqli_fetch_assoc($cart_item_result)) {
                $quantity = $cart_row["quantity"];
                $product_id = $cart_row["product_id"];
                
                // Fetch product details
                $product_details_query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
                $product_details_result = mysqli_query($link, $product_details_query);
                
                if ($product_details_result && mysqli_num_rows($product_details_result) > 0) {
                    $product_row = mysqli_fetch_assoc($product_details_result);
                    $product_name = htmlspecialchars($product_row['product_name']);
                    $product_price = htmlspecialchars($product_row['price']);
                    $image_path = htmlspecialchars($product_row['image_path']);
                    
                    // Insert order into order_details table
                    $query = "
                        INSERT INTO order_details (user_id, product_id, product_name, product_price, full_name, address, pincode, mobile_number, quantity, payment_method, email, image_path)
                        VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$full_name', '$address', '$pincode', '$mobile', '$quantity', '$payment_method', '$email', '$image_path')
                    ";
                    
                    $result = mysqli_query($link, $query);
                    if ($result) {
                        echo "Product inserted successfully<br>";
                    } else {
                        $dberror = 'Error while inserting the item into order_details.';
                    }

                    header("Location:buy_cart_email.php?email=" . urlencode($email) . "&user_id=" . urlencode($user_id) . "&quantity=" . urlencode($quantity) . "&name=" . urlencode($full_name));
                }
            }
        }
    }
}

// Fetch address details 
$address_query = "SELECT * FROM address WHERE user_id='$user_id'";
$address_result = mysqli_query($link, $address_query);
$address_data = mysqli_fetch_assoc($address_result);

mysqli_close($link);
ob_end_flush();
?>

<div class="container mt-5 col-md-6">
    <h2 class="mb-4 text-center"style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;margin-right:14px">Address Details</h2>
    <div class="col-md-10 my-5" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;">
        <div class="card d-flex-justify-content-center p-4" style="margin-left:60px;">
            <form method="POST">
                <span class="error" style="color:red;"><?php echo $dberror; ?></span>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $address_data['name'] ?? ''; ?>">
                        <span class="error" style="color:red;"><?php echo $nameErr; ?></span>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $address_data['email'] ?? ''; ?>">
                        <span class="error" style="color:red;"><?php echo $emailErr; ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo $address_data['address'] ?? ''; ?></textarea>
                    <span class="error" style="color:red;"><?php echo $addressErr; ?></span>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo $address_data['pincode'] ?? ''; ?>">
                        <span class="error" style="color:red;"><?php echo $pincodeErr; ?></span>
                    </div>
                    <div class="col-md-6">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $address_data['mobile_no'] ?? ''; ?>">
                        <span class="error" style="color:red;"><?php echo $mobileErr; ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <div>
                        <input type="radio" id="cod" name="payment_method" value="COD" <?php echo ($address_data['payment_method'] ?? '') === 'COD' ? 'checked' : ''; ?>>
                        <label for="cod">Cash on Delivery</label><br>
                        <input type="radio" id="online" name="payment_method" value="Online" <?php echo ($address_data['payment_method'] ?? '') === 'Online' ? 'checked' : ''; ?>>
                        <label for="online">Online Payment</label><br>
                    </div>
                    <span class="error" style="color:red;"><?php echo $paymentMErr; ?></span>
                </div>

                <button type="submit" class="btn btn-primary w-100" name="submit">Place Order</button>
            </form>
        </div>
    </div>
</div>

<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>
