<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
if (!isset($_COOKIE['login_id'])) {
    header("Location:welcome.php");
    exit();
}
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>";
echo "
<div class='d-flex justify-content-start ms-1 my-2' style='margin-top=10px;'>
    <a href='/E-commerce website/templates/welcome.php' class='text-decoration-none'>
        <i class='fa fa-arrow-left' aria-hidden='true' style='font-size: 1.5rem;'></i>
    </a>
</div>";
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_GET['user_id'];
$query = "SELECT * FROM cart_details WHERE user_id = '$user_id'";
$result = mysqli_query($link, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $total_price = 0;
    $total_items = 0;
    //display products in cart
    echo "
    <div style='display: flex; justify-content: space-between; align-items: flex-start; margin-top: 20px; margin-left:14px;'>
        <!-- Left Section: Cart Items -->
        <div style='width: 60%; border: 1px solid #ccc; border-radius: 8px; padding: 20px;'>
            <h3>Items in Your Cart</h3>
            <table style='width: 100%; border-collapse: collapse;'>
                <thead>
                    <tr>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Image</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Product Name</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Price</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Quantity</th>
                        <th style='border-bottom: 1px solid #ddd; padding: 10px;'>Total</th>
                    </tr>
                </thead>
                <tbody>
    ";
   
    while ($row = mysqli_fetch_assoc($result)) {
        $product_name = htmlspecialchars($row['product_name']);
        $product_price = $row['price'];
        $quantity = $row['quantity'];
        $total_price += $product_price * $quantity;
        $total_items += $quantity;
        $image_path = htmlspecialchars($row['image_path']);
        $row_total = $product_price * $quantity;

        echo "
        <tr>
            <td style='padding: 10px; text-align: center;'>
                <img src='/E-commerce website/admin/$image_path' style='max-width: 80px; height: auto; border-radius: 5px;' alt='$product_name'>
            </td>
            <td style='padding: 10px;'>$product_name</td>
            <td style='padding: 10px;'>₹$product_price</td>
            <td style='padding: 10px;'>$quantity</td>
            <td style='padding: 10px;'>₹$row_total</td>
        </tr>
        ";
    }
     //add address details code
   

    echo "
                </tbody>
            </table>";
        //php code go here 
        $full_name_i='';
        $email_i='';
         $pincode_i='';
        $mobile_number_i='';
        $address_i='';
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
            if (empty($pincode)) {
                $pincodeErr = "Pincode is require.";
                $errors[] = $pincodeErr;
            }
            else{
                if(!preg_match("/^\d{6}$/", $pincode))
                {
                    $pincodeErr = "Invalid pincode require 6 digit.";
                    $errors[] = $pincodeErr;
                }
            }
            if (empty($mobile) || !preg_match("/^[1-9]\d{9}$/", $mobile)) {
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
        $buyerName = isset($address_data['name']) ? $address_data['name'] : "";
        $email = isset($address_data['email']) ? $address_data['email'] : "";
        $pincode=isset($address_data['pincode']) ? $address_data['pincode'] : "";
        $mobile=isset($address_data['mobile_no']) ? $address_data['mobile_no'] : "";
        $address = isset($address_data['address']) ?$address_data['address'] :"";
        mysqli_close($link);
        ob_end_flush();
        ?>
        <div class="container mt-5 ">
    <h2 class="mb-4 text-center"style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;margin-right:14px">Address Details</h2>
    <div class="col-md-10 my-5" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;">
        <div class="card d-flex-justify-content-center p-4" style="margin-left:60px; width:500px">
            <form method="POST">
                <span class="error" style="color:red;"><?php echo $dberror; ?></span>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : (isset($buyerName) && $buyerName ? htmlspecialchars($buyerName) : htmlspecialchars($full_name_i)); ?>">
                        <span class="error" style="color:red;"><?php echo $nameErr; ?></span>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : (isset($email) && $email ? htmlspecialchars($email) : htmlspecialchars($email_i)); ?>">
                        <span class="error" style="color:red;"><?php echo $emailErr; ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : (isset($address) && $address ? htmlspecialchars($address) : htmlspecialchars($address_i)); ?></textarea>
                    <span class="error" style="color:red;"><?php echo $addressErr; ?></span>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo isset($_POST['pincode']) ? htmlspecialchars($_POST['pincode']) : (isset($pincode) && $pincode ? htmlspecialchars($pincode) : htmlspecialchars($pincode_i)); ?>">
                        <span class="error" style="color:red;"><?php echo $pincodeErr; ?></span>
                    </div>
                    <div class="col-md-6">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : (isset($mobile) && $mobile ? htmlspecialchars($mobile) : htmlspecialchars($mobile_number_i)); ?>">
                        <span class="error" style="color:red;"><?php echo $mobileErr; ?></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <div>
                        <input type="radio" id="cod" name="payment_method" value="COD" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'COD') ? 'checked' : ''; ?>>
                        <label for="cod">Cash on Delivery</label><br>
                        <input type="radio" id="online" name="payment_method" value="Online" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Online') ? 'checked' : ''; ?>>
                        <label for="online">Online Payment</label><br>
                    </div>
                    <span class="error" style="color:red;"><?php echo $paymentMErr; ?></span>
                </div>

                <button type="submit" class="btn btn-primary w-100" name="submit" >
    Place Order
</button>

            </form>
        </div>
    </div>
</div>
<html>
    <head>
        <title>EzyBuy-Check Out</title>
    </head>
</html>



<?php

// Sample dynamic price (replace with your actual dynamic price calculation)

 // Example price per item


// Static values
$discount = 0; // Static discount
$delivery_charges_original = 120; // Static original delivery charges
$delivery_charges = 0; // Static delivery charges (e.g., free delivery)
$packaging_fee = 0; //Static packaging fee

// Calculate the final total price


echo "  
</div>

<div style='width: 35%; padding: 20px; border: 1px solid #ccc; border-radius: 8px; margin-right: 25px; position: sticky; top: 90px; z-index: 100; background-color: white;'>
    <h2 style='text-align: center; margin-bottom: 20px;'>Price Details</h2>";

    echo "<table style='width: 100%; border-collapse: collapse;'>";

    echo "<tr>";
    echo "<td style='padding: 8px 0;'>Price (" . $total_items . " items)</td>";
    echo "<td style='text-align: right; padding: 8px 0;'>₹" . number_format($total_price, 2) . "</td>";
    echo "</tr>";

   

    echo "<tr>";
    echo "<td style='padding: 8px 0;'>Delivery Charges</td>";
    if ($delivery_charges_original > 0 && $delivery_charges == 0) { // Free Delivery
        echo "<td style='text-align: right; padding: 8px 0;'><del>₹" . number_format($delivery_charges_original, 2) . "</del> <span style='color:green;'>Free</span></td>";
    } 
    echo "</tr>";

   


    echo "<tr style='border-top: 1px solid #ccc;'>";
    echo "<td style='padding: 12px 0; font-weight: bold;'>Total Amount</td>";
    echo "<td style='text-align: right; padding: 12px 0; font-weight: bold;'>₹" . number_format($total_price, 2) . "</td>";
    echo "</tr>";

   
    echo "</table>";

echo "</div>
</div>
";


echo "</div>
</div>
";


} else {
    echo "No products found in your cart.";
}
//echo "address details are"; 

?>
<html>
    <head>
        <title>EzyBuy-Check-Out</title>
    </head>
</html>
<?php
//include("/home/web/public_html/E-commerce website/includes/footer.php");
?>