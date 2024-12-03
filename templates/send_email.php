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
    $mail->addAddress('sagarsupekar8857@gmail.com', 'Sagar Supekar');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Order Confirmation - Your Order Details';
    
    // HTML Body content
    $mail->Body = "<html>
<head>
    <style>
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
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class='header'>EzyBuy - Empowering Innovation</div>
    <p>Dear $buyerName,</p>
    <p>Thank you for your order!</p>
    <p>We are pleased to confirm the details of your recent order:</p>
    <table>
        <tr>
            <th>Detail</th>
            <th>Information</th>
        </tr>
        <tr>
            <td><strong>Order Date:</strong></td>
            <td>$orderDate</td>
        </tr>
        <tr>
            <td><strong>Estimated Delivery Date:</strong></td>
            <td>$deliveryDate</td>
        </tr>
        <tr>
            <td><strong>Price:</strong></td>
            <td>$price</td>
        </tr>
        <tr>
            <td><strong>Payment Method:</strong></td>
            <td>$paymentMethod</td>
        </tr>
        <tr>
            <td><strong>Shipping Address:</strong></td>
            <td>$placedAddress</td>
        </tr>
        <tr>
            <td><strong>Quantity:</strong></td>
            <td>$quantity</td>
        </tr>
    </table>
    <p>We will notify you once your order is shipped. If you have any questions, feel free to contact us.</p>
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
