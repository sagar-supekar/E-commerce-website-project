<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../phpmailerfiles/Exception.php';
require '../phpmailerfiles/PHPMailer.php';
require '../phpmailerfiles/SMTP.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Capture the data from the URL query parameters
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $user_id = $_GET['user_id'];
    $email = $_GET['email'];
    $quantity= $_GET['quantity'];
    $name=$_GET['name'];
    // Query to fetch all order details for the given user
    $query = "SELECT * FROM cart_details WHERE user_id='$user_id'";
    $result = mysqli_query($link, $query);

    // Fetch product name, quantity, and price for each product
    $product_name = [];
    $product_price = [];
    $product_qty = [];
    $total_price = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_name[] = $row["product_name"];
            $product_price[] = $row["price"];
            $product_qty[] = $row["quantity"];
            $product_id= $row["product_id"];
           // print_r($row);
           //update qunatity in quantity table
            $update_quantity_query = "UPDATE e_product_details SET quantity = quantity - $quantity WHERE product_id = '$product_id'";
            mysqli_query($link, $update_quantity_query);

            //delete the cart tems from cart
            $delete_cart_query = "DELETE FROM cart_details WHERE user_id='$user_id' AND product_id='$product_id'";
            mysqli_query($link, $delete_cart_query);
        }
    }

    // Calculate the total price for all products
    $total_price = 0;
    for ($i = 0; $i < count($product_name); $i++) {
        $total_price += $product_price[$i] * $product_qty[$i];
    }

    //random date
    $random_days = rand(3, 9);
    $deliveryDate = date('Y-m-d', strtotime("+$random_days days"));

    // Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                         // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                     // Enable SMTP authentication
    $mail->Username   = 'sagarsupekar8857@gmail.com';             // SMTP username
    $mail->Password   = 'qmam kvwy lwea pmcn';                    // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              // Enable implicit TLS encryption
    $mail->Port       = 465;                                      // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom('sagarsupekar8857@gmail.com', 'Sagar');
    $mail->addAddress($email, 'Sagar Supekar');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Order Confirmation - Your Order Details';
    
    // HTML Body content
    $table_rows = "";
    for ($i = 0; $i < count($product_name); $i++) {
        $table_rows .= "
            <tr>
                <td>{$product_name[$i]}</td>
                <td>{$product_qty[$i]}</td>
                <td> ".number_format(($product_price[$i] * $product_qty[$i]),2). "</td>
            </tr>
        ";
    }

    $mail->Body = "<html>
<head>
    <style>
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-top: 6px;
            width: 100%;
            height: 50px;
            color: white;
            background-color: #363d69;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class='header'>EzyBuy - Empowering Innovation</div>
    <p>Dear $name,</p>
    <p>Thank you for your order!</p>
    <p>Here are the details of your recent order:</p>
    
    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
        $table_rows
        <tr>
            <th colspan='2'>Total Price</th>
            <td><strong>$total_price</strong></td>
        </tr>
    </table>
    <br>
    <p>Your order will be delivered on <strong>$deliveryDate</strong>.</p>
    <br>
    <p>Thanks & Regards</p>
    <p>EzyBuy - Empowering Innovation</p>
</body>
</html>";
                    //update the quantity count 
                   
    $mail->send();
    header("Location: /E-commerce website/templates/order_history.php?message=Order placed successfully");
    exit;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
