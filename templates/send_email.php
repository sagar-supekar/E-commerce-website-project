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
    $buyerName = $_GET['buyerName'];
    $orderDate = $_GET['orderDate'];
    $price = $_GET['price'];
    $paymentMethod = $_GET['paymentMethod'];
    $placedAddress = $_GET['placedAddress'];
    $quantity = $_GET['quantity'];
    $deliveryDate = $_GET['deliveryDate'];
    $email= $_GET['email'];
    $product_name = isset($_GET['product_name']) ? $_GET['product_name'] : 'Unknown Product';
    $url= $_GET['url'];
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
    $mail->Body = "<html>
<head>
    <style>
         .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-top: 6px;
            width:100%;
            height: 50px;
            color:white;
            background-color:#363d69;
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
    <p>Dear $buyerName,</p>
    <p>Thank you for your order!</p>
    <p>Here is your recent order details:</p>
    
    <table>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>
        <tr>
            <td>$product_name</td>
            <td>$quantity</td>
            <td>" . ($price * $quantity) . "</td>
        </tr>
    </table>
    <br>
    <p>Your order will be delivered on <strong>$deliveryDate</strong>.</p>
    <br>
    <p>Thanks & Regards</p>
    <p>EzyBuy - Empowering Innovation</p>
</body>
</html>
";

    $mail->send();
    header("Location: /E-commerce website/templates/order_history.php?message=Order placed successfully");
    exit;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
