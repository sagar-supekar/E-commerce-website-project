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

$address_query = "SELECT * FROM address WHERE user_id = '$user_id'";
$address_result = mysqli_query($link, $address_query);
$address = mysqli_fetch_assoc($address_result);

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

// Fetch user address details from the address table
$address_available = !empty($address);

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $full_name=$_POST['first_name'];
    $email=$_POST['email'];
    $pincode=$_POST['pincode'];
    $mobile_number=$_POST['mobile_number'];
    $add=$_POST['address'];
    $buyerName = isset($_POST["full_name"]) ? $_POST["full_name"] : ''; // Check if the 'full_name' field is set
    $errors = [];
    $quantity = trim($_POST["quantity"]);
    $payment_method = $_POST["payment_method"] ?? "";

    // Validation
    if (empty($full_name)) {
        $nameErr = "Full name is required";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $full_name)) {
        $nameErr = "Only letters and white space allowed";
    }

    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($add)) {
        $addressErr = "Address is required";
    }

    if (empty($pincode)) {
        $pincodeErr = "Pincode is required";
    } elseif (!preg_match("/^\d{6}$/", $pincode)) {  // Check if the pincode is exactly 6 digits
        $pincodeErr = "Pincode must be a 6-digit number";
    }

    if (empty($mobile_number)) {
        $mobileErr = "Mobile number is required";
    } elseif (!preg_match("/^[1-9]\d{9}$/", $mobile_number)) {
        $mobileErr = "Mobile number length should be 10";
    }
    if (empty($quantity) ) {
        $quantityErr = "Quantity is require.";
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
    if (empty($errors)&&empty($nameErr)&&empty($emailErr)&&empty($mobileErr)&&empty($pincodeErr)&&empty($addressErr)&&empty($quantityErr)) {
        $product_name = htmlspecialchars($product['product_name']);
        $product_price = htmlspecialchars($product['price']);
        $image_path = htmlspecialchars($product['image_path']);
        
        // Fetch address details
        if (mysqli_num_rows($address_result) > 0) {
            $buyerName = $address['name'];
            $mobileNumber = $address['mobile_no'];
            $user_address = $address['address'];
            $pincode = $address['pincode'];
        } else {
            $buyerName = "";
            $mobileNumber = "";
            $user_address = "";
            $pincode = "";  
        }
        
        // Insert order
        $order_query = "
            INSERT INTO order_details (user_id, product_id, full_name, product_name, product_price, quantity, payment_method, image_path, mobile_number, address, pincode)
            VALUES ('$user_id', '$product_id', '$buyerName', '$product_name', '$product_price', '$quantity', '$payment_method', '$image_path', '$mobileNumber', '$user_address', '$pincode')
        ";
        
        if (mysqli_query($link, $order_query)) {
            // Update stock
            $update_query = "UPDATE e_product_details SET quantity = quantity - $quantity WHERE product_id = '$product_id'";
            mysqli_query($link, $update_query);

            // Redirect to send_email.php with order details
            $buyerName = urlencode($address['name']);
            $price = urlencode($product['price'] * $quantity); // Total price
            $paymentMethod = urlencode($payment_method);
            $placedAddress = urlencode($address['address']);
            $quantity = urlencode($quantity);
            $email = urlencode($address['email'])?urlencode($address['email']):$_POST['email'];
            $product_name = urlencode($product['product_name']);

            $email_url = "/E-commerce website/templates/send_email.php?" . "buyerName=$buyerName" . "&price=$price" . "&paymentMethod=$paymentMethod" . "&placedAddress=$placedAddress" . "&quantity=$quantity" . "&email=$email" . "&product_name=$product_name";

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
<?php
// Fetch user address details from the address table
//$address_available = !empty($address);
 $full_name_i = '';
 $email_i='';
 $pincode_i='';
$mobile_number_i='';
$address_i='';
// Fetch address details safely
$buyerName = isset($address['name']) ? $address['name'] : "";
$email = isset($address['email']) ? $address['email'] : "";
$user_address = isset($address['address']) 
    ? $address['address'] 
    : (isset($_POST['address']) 
        ? htmlspecialchars($_POST['address']) 
        : (isset($add) && $add 
            ? htmlspecialchars($add) 
            : htmlspecialchars($address_i)
        )
    );
$pincode = isset($address['pincode']) ? $address['pincode'] : "";
$mobileNumber = isset($address['mobile_no']) ? $address['mobile_no'] : "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    //$first_name=$_POST['first_name'];
    // $email=$_POST['email'];
    // $pincode=$_POST['pincode'];
    // $mobile_number=$_POST['mobile_number'];
    // $address=$_POST['address'];
}
?>
<html>
    <head>
        <title>Buy Now</title>
    </head>
</html>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Buy Product</h2>

    <?php if ($product): ?>
        <div class="row">
            <!-- Product Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="/E-commerce website/admin/<?php echo htmlspecialchars($product['image_path']); ?>" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="max-width: 100%;">
                        <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                        <p class="text-muted">Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Address & Order Form -->
            <div class="col-md-6">
                <div class="card p-4">
                    <form method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="first_name" 
                                value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : (isset($buyerName) && $buyerName ? htmlspecialchars($buyerName) : htmlspecialchars($full_name_i)); ?>">
                                <small class="text-danger"><?php echo $nameErr; ?></small>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($email) && $email ? htmlspecialchars($email) : htmlspecialchars($email_i));?>">
                                <small class="text-danger"><?php echo $emailErr; ?></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="3" name="address"><?php echo $user_address; ?></textarea>
                            <small class="text-danger"><?php echo $addressErr; ?></small>
                        </div>
                        <div class="row mb-3"> 
                            <div class="col-md-6">
                                <label for="pincode" class="form-label">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['pincode']) : (isset($pincode) && $pincode ? htmlspecialchars($pincode) : htmlspecialchars($pincode_i));?>">
                                <small class="text-danger"><?php echo $pincodeErr; ?></small>
                            </div>
                            <div class="col-md-6">
                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobileNumber" name="mobile_number" value="<?php echo isset($_POST['mobile_number']) ? htmlspecialchars($_POST['mobile_number']) : (isset($mobileNumber) && $mobileNumber ? htmlspecialchars($mobileNumber) : htmlspecialchars($mobile_number_i));?>">
                                <small class="text-danger"><?php echo $mobileErr; ?></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>">
                            <small class="error" style="color:red;"><?php echo $quantityErr; ?></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div>
                                <input type="radio" id="cod" name="payment_method" value="COD" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'COD') ? 'checked' : ''; ?>>
                                <label for="cod">Cash on Delivery</label><br>
                                <input type="radio" id="online" name="payment_method" value="Online" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Online') ? 'checked' : ''; ?>>
                                <label for="online">Online Payment</label><br>
                            </div>
                            <small class="error" style="color:red;"><?php echo $paymentMErr; ?></small>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
