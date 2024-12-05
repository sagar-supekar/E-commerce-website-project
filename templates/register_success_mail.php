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
    $buyerName = $_GET['name'];
    $email= $_GET['email'];
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
    $mail->Subject = 'Thank You for Registering with EzyBuy!';
    
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
        .sec-header{
            text-align: center;
            font-size: 24px;
            font-weight: bold;
             margin-top: 20px;
            margin-bottom: 20px;
            }
    </style>
</head>
<body>
      <div class='header'>EzyBuy - Empowering Innovation</div>
      <p>Dear $buyerName,</p>
      <p class='sec-header'>Thanks for Registering with EzyBuy</p>
      <p> We are excited to have you on board</p>
      <p>You can now explore our platform and enjoy a seamless shopping experience. </p>
      <br>
      <p>Thanks & Regards</p>
      <p>EzyBuy - Empowering Innovation</p>
</body>
</html>
";

    $mail->send();
    header("Location: /E-commerce website/templates/login.php?message=Register mail send successfully&url=$url");
    exit;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
