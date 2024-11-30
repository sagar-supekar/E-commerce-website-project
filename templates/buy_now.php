<?php
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
ini_set('display_errors', 1);
error_reporting(E_ALL);

$errors = [];
$product = null;


$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $user_id=$_GET['user_id'];
    $product_id=$_GET['product_id'];
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";  
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = htmlspecialchars($row['price']);
        $product_description = htmlspecialchars($row['description']);
        $image_path = htmlspecialchars($row['image_path']);
        $category = htmlspecialchars($row['category']);
        $price=htmlspecialchars($row['price']);
    }   
    
    
    $full_name = trim($_POST["full_name"]);
    $address = trim($_POST["address"]);
    $pincode = trim($_POST["pincode"]);
    $mobile = trim($_POST["mobile"]);
    $quantity=trim($_POST["quantity"]);
    $payment_method = isset($_POST["payment_method"]) ? $_POST["payment_method"] : "";
 
    // Validate inputs
    if (empty($full_name)) $errors[] = "Full Name is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($pincode) || !preg_match("/^\d{6}$/", $pincode)) $errors[] = "Invalid Pincode. Must be 6 digits.";
    if (empty($mobile) || !preg_match("/^\d{10}$/", $mobile)) $errors[] = "Invalid Mobile Number. Must be 10 digits.";
    if (empty($payment_method)) $errors[] = "Please select a Payment Method.";

    if (empty($errors)) {
        // Insert order into the database
        $query = "
            INSERT INTO order_details (user_id,product_id, product_name,product_price,full_name, address, pincode, mobile_number,quantity,payment_method,price)
            VALUES (' $user_id','$product_id', '$product_name','$product_price','$full_name', '$address', '$pincode', '$mobile',$quantity,'$payment_method','$price')
        ";

        if (mysqli_query($link, $query)) {

            echo "<p style='color: green; text-align: center;'>Order placed successfully!</p>";
            $query="UPDATE e_product_details SET quantity=quantity-$quantity where product_id='$product_id'";
            $result=mysqli_query($link, $query);
            if($result)
            {
                echo "quantity is minimize ";
            }
            else{
                echo "error while minimizining";
            }
        } else {
            echo "<p style='color: red; text-align: center;'>Error placing order: " . mysqli_error($link) . "</p>";
        }
    }
} elseif (isset($_GET["product_id"])) {
    // Fetch product details
    $product_id = mysqli_real_escape_string($link, $_GET["product_id"]);
    $query = "SELECT * FROM e_product_details WHERE product_id = '$product_id'";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<p style='color: red; text-align: center;'>Product not found.</p>";
    }
} else {
    echo "<p style='color: red; text-align: center;'>Invalid request.</p>";
}

mysqli_close($link);
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
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form  method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($full_name) ? $full_name : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($address) ? $address : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo isset($pincode) ? $pincode : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo isset($mobile) ? $mobile : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo isset($pincode) ? $pincode : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div>
                                <input type="radio" id="cod" name="payment_method" value="COD" <?php echo (isset($payment_method) && $payment_method == 'COD') ? 'checked' : ''; ?>>
                                <label for="cod">Cash on Delivery</label><br>
                                <input type="radio" id="online" name="payment_method" value="Online" <?php echo (isset($payment_method) && $payment_method == 'Online') ? 'checked' : ''; ?>>
                                <label for="online">Online Payment</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
