<?php
session_start();
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the address from the database using add_id
$user_id = $_COOKIE['login_id'];
$add_id = $_SESSION['add_id'];
$query = "SELECT * FROM address WHERE user_id = '$user_id' AND add_id = '$add_id'";
$result = mysqli_query($link, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $address = mysqli_fetch_assoc($result);
} else {
    header('Location: address.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Address</title>
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
        .address-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .address-container h2 {
            margin-bottom: 1.5rem;
            color: #343a40;
        }
        .address-container p {
            margin: 0.5rem 0;
        }
        .btn-edit {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="address-container">
        <h2>Your Address</h2>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($address['name'], ENT_QUOTES) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($address['email'], ENT_QUOTES) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($address['address'], ENT_QUOTES) ?></p>
        <p><strong>Pincode:</strong> <?= htmlspecialchars($address['pincode'], ENT_QUOTES) ?></p>
        <p><strong>Mobile Number:</strong> <?= htmlspecialchars($address['mobile_no'], ENT_QUOTES) ?></p>
        <a href="edit_address.php" class="btn btn-primary btn-edit">Edit Address</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
