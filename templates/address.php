<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
//session_start();
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
// if (!isset($_SESSION['login_id'])) {
//     header('Location: welcome.php');
//     exit();
// }

// Handle address form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $mobile_number = $_POST['mobile_number'];
    
    // Validate input
    $errors = [];
    if (empty($full_name) || empty($email) || empty($address) || empty($pincode) || empty($mobile_number)) {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (!is_numeric($mobile_number) || strlen($mobile_number) != 10) {
        $errors[] = 'Invalid mobile number.';
    }
    if (!is_numeric($pincode) || strlen($pincode) != 6) {
        $errors[] = 'Invalid pincode.';
    }
    
    // If no errors, insert address into database
    if (empty($errors)) {
        $user_id = $_COOKIE['login_id'];
        
        $query = "INSERT INTO address(user_id, name, email, address, pincode, mobile_no) 
                  VALUES ('$user_id', '$full_name', '$email', '$address', '$pincode', '$mobile_number')";
        if (mysqli_query($link, $query)) {
            $_SESSION['add_id'] = mysqli_insert_id($link); // Set the add_id in session
            if (headers_sent($file, $line)) {
                die("Headers already sent in $file on line $line");
            }
            header('Location:buy_now.php');
            echo("address store successfully");
            //exit();
        } else {
            $errors[] = 'Error saving address. Please try again.';
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Address</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .add-form{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 10vh;
            background-color: #f8f9fa;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="add-form">
    <div class="form-container">
        <h2 class="text-center mb-4">Enter Address</h2>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='text-danger'>$error</p>";
            }
        }
        ?>
        <form method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="pincode" class="form-label">Pincode:</label>
                <input type="text" class="form-control" id="pincode" name="pincode" required>
            </div>
            <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number:</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Address</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
