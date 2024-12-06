<?php
if (!isset($_COOKIE['login_id'])) {
    header("Location:welcome.php");
    exit();
}
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>
<?php
ob_start(); // Start output buffering
ini_set('display_errors', 1);
error_reporting(E_ALL);

$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['login_id'])) {
    header('Location: welcome.php');
    exit();
}


$nameErr = $emailErr = $addressErr = $mobileErr = $pincodeErr = $emptyErr = '';
$full_name = $email = $address = $pincode = $mobile_number = '';


$user_id = $_SESSION['login_id'];

// Fetch existing address details if available
$query = "SELECT * FROM address WHERE user_id = '$user_id' LIMIT 1";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $full_name = htmlspecialchars($row['name']);
    $email = htmlspecialchars($row['email']);
    $address = htmlspecialchars($row['address']);
    $pincode = htmlspecialchars($row['pincode']);
    $mobile_number = htmlspecialchars($row['mobile_no']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $full_name = test_input($_POST['full_name']);
    $email = test_input($_POST['email']);
    $address = test_input($_POST['address']);
    $pincode = test_input($_POST['pincode']);
    $mobile_number = test_input($_POST['mobile_number']);

    // Validate fields
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

    if (empty($address)) {
        $addressErr = "Address is required";
    }

    if (empty($pincode)) {
        $pincodeErr = "Pincode is required";
    } elseif (!preg_match("/^\d{6}$/", $pincode)) {
        $pincodeErr = "Pincode must be a 6-digit number";
    }

    if (empty($mobile_number)) {
        $mobileErr = "Mobile number is required";
    } elseif (!preg_match("/^[6-9]\d{9}$/", $mobile_number)) {
        $mobileErr = "Mobile number length should be 10";
    }

    // If no errors, insert or update the address
    if (empty($nameErr) && empty($emailErr) && empty($addressErr) && empty($pincodeErr) && empty($mobileErr)) {
        if ($result && mysqli_num_rows($result) > 0) {
            // Update existing address
            $query = "UPDATE address SET 
                      name = '$full_name', 
                      email = '$email', 
                      address = '$address', 
                      pincode = '$pincode', 
                      mobile_no = '$mobile_number' 
                      WHERE user_id = '$user_id'";
        } else {
            // Insert new address
            $query = "INSERT INTO address(user_id, name, email, address, pincode, mobile_no) 
                      VALUES ('$user_id', '$full_name', '$email', '$address', '$pincode', '$mobile_number')";
        }

        if (mysqli_query($link, $query)) {
            $successMessage = "Address updated successfully!";
        } else {
            $emptyErr = 'Error saving address. Please try again.';
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
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 10vh;
            background-color: #f8f9fa;
            margin-top:20px;
        }
        .form-container {
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 650px;
        }
    </style>
</head>
<body>
<div class="add-form">
    <div class="form-container">
        <h2 class="text-center mb-4">Profile Details</h2>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="full_name" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                    <small class="text-danger"><?php echo $nameErr; ?></small>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <small class="text-danger"><?php echo $emailErr; ?></small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pincode" class="form-label">Pincode:</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo htmlspecialchars($pincode); ?>">
                    <small class="text-danger"><?php echo $pincodeErr; ?></small>
                </div>
                <div class="col-md-6">
                    <label for="mobile_number" class="form-label">Mobile Number:</label>
                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($mobile_number); ?>">
                    <small class="text-danger"><?php echo $mobileErr; ?></small>
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                <small class="text-danger"><?php echo $addressErr; ?></small>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Address</button>
        </form>
        <div class="mt-3 text-center">
            
            <a href="welcome.php" class="btn btn-secondary w-100">HOME</a>
        </div>
    </div>
</div>



    <!-- Bootstrap JS -->
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>