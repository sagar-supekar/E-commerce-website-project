<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
// if (!isset($_SESSION['login_id'])) {
//     header('Location: welcome.php');
//     exit();
// }

// Retrieve the address from the database using add_id
$user_id = $_COOKIE['login_id'];
$add_id = $_SESSION['add_id'];
$user_id_n=$_GET['user_id'];
$product_id_n= $_GET['product_id'];
$query = "SELECT * FROM address WHERE user_id = '$user_id' AND add_id = '$add_id'";
$result = mysqli_query($link, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $address = mysqli_fetch_assoc($result);
} else {
    header('Location: address.php');
    exit();
}

// Handle form submission to update address
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $address_text = $_POST['address'];
    $pincode = $_POST['pincode'];
    $mobile_number = $_POST['mobile_number'];

    // Validate input
    $errors = [];
    if (empty($full_name) || empty($email) || empty($address_text) || empty($pincode) || empty($mobile_number)) {
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

    // If no errors, update address in database
    if (empty($errors)) {
        $update_query = "UPDATE address SET name = '$full_name', email = '$email', 
                         address = '$address_text', pincode = '$pincode', mobile_no = '$mobile_number' 
                         WHERE add_id = '$add_id' AND user_id = '$user_id'";

        if (mysqli_query($link, $update_query)) {
            header("Location: buy_now.php?user_id=$user_id_n&product_id=$product_id_n");
            exit();
        } else {
            $errors[] = 'Error updating address. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center mb-4">Edit Address</h2>
        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p class='text-danger'>$error</p>";
            }
        }
        ?>
        <form  method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" 
                       value="<?= htmlspecialchars($address['name'], ENT_QUOTES) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= htmlspecialchars($address['email'], ENT_QUOTES) ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?= 
                    htmlspecialchars($address['address'], ENT_QUOTES) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="pincode" class="form-label">Pincode:</label>
                <input type="text" class="form-control" id="pincode" name="pincode" 
                       value="<?= htmlspecialchars($address['pincode'], ENT_QUOTES) ?>" required>
            </div>
            <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number:</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" 
                       value="<?= htmlspecialchars($address['mobile_no'], ENT_QUOTES) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Address</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
