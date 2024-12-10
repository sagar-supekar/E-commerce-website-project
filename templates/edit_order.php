<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
// Include headers
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-5 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/order_history.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";

$nameErr = $addressErr = $pincodeErr = $mobileErr = $quantityErr = $paymentMErr = '';
$order = null;
$update_order_message='';
if(isset($_GET['update_order_message']))
{
    $update_order_message=$_GET['update_order_message'];
}
echo $update_order_message;
// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $errors = [];
    $order_id = $_GET['order_id'] ?? null;

    if (!$order_id) {
        die("Invalid request: Missing order_id.");
    }

    // Form input
    $full_name = trim($_POST["full_name"]);
    $address = trim($_POST["address"]);
    $pincode = trim($_POST["pincode"]);
    $mobile = trim($_POST["mobile"]);

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

    if (empty($errors)) {
        // Update the order in the database
        $query = "
            UPDATE order_details
            SET full_name = '$full_name', address = '$address', pincode = '$pincode', 
                mobile_number = '$mobile'
            WHERE order_id = '$order_id'
        ";

        if (mysqli_query($link, $query)) {
            header("Location: /E-commerce website/templates/order_history.php?update_order_message=order update successfully");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Error updating order: " . mysqli_error($link) . "</p>";
        }
    }
}
 
// Fetch order details for display
if (isset($_GET["order_id"])) {
    $order_id = mysqli_real_escape_string($link, $_GET["order_id"]);
    $query = "SELECT * FROM order_details WHERE order_id = '$order_id'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);
    } else {
        echo "<p style='color: red; text-align: center;'>Order not found.</p>";
    }
}

mysqli_close($link);
ob_end_flush();
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Update Order</h2>

    <?php if ($order): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="/E-commerce website/admin/<?php echo $order['image_path']; ?>" class="img-fluid rounded mb-3" alt="<?php echo $order['product_name']; ?>" style="max-width: 100%;">
                        <h4><?php echo $order['product_name']; ?></h4>
                        <p class="text-muted">Price: ₹<?php echo $order['product_price']; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($order['full_name']) ? $order['full_name'] : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $nameErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($order['address']) ? $order['address'] : ''; ?></textarea>
                            <span class="error" style="color:red;"><?php echo $addressErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo isset($order['pincode']) ? $order['pincode'] : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $pincodeErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo isset($order['mobile_number']) ? $order['mobile_number'] : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $mobileErr; ?></span>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo isset($order['quantity']) ? $order['quantity'] : ''; ?>">
                            <span class="error" style="color:red;"><?php echo $quantityErr; ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div>
                                <input type="radio" id="cod" name="payment_method" value="COD" <?php echo (isset($order['payment_method']) && $order['payment_method'] == 'COD') ? 'checked' : ''; ?>>
                                <label for="cod">Cash on Delivery</label><br>
                                <input type="radio" id="online" name="payment_method" value="Online" <?php echo (isset($order['payment_method']) && $order['payment_method'] == 'Online') ? 'checked' : ''; ?>>
                                <label for="online">Online Payment</label>
                                <span class="error" style="color:red;"><?php echo $paymentMErr; ?></span>
                            </div>
                        </div> -->
                        <button type="submit" class="btn btn-success w-100" name="submit">Update Order</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
