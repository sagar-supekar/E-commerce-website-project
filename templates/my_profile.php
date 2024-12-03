<?php
// Start the session
session_start();

// Check if the user is logged in (based on the 'login_id' session variable)
if (!isset($_SESSION['login_id'])) {
    // If not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

// Include the database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

// Check the connection
if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}

// Fetch user details from the database based on the login_id
$login_id = $_SESSION['login_id'];
$query = "SELECT * FROM e_login_table WHERE id = '$login_id'";
$result = mysqli_query($link, $query);

// Check if user exists
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // If no user is found (e.g., invalid login_id), redirect to login
    header('Location: login.php');
    exit();
}

// Fetch order history if needed (optional)
$order_query = "SELECT * FROM orders WHERE user_id = '$login_id'";
$order_result = mysqli_query($link, $order_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            margin-top: 20px;
        }
        .profile-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header h2 {
            font-size: 2rem;
            color: #333;
        }
        .profile-header img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .user-details {
            margin-top: 30px;
        }
        .user-details h4 {
            color: #333;
        }
        .user-details p {
            font-size: 1rem;
            color: #666;
        }
        .order-history table {
            width: 100%;
            margin-top: 40px;
        }
        .order-history th, .order-history td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .order-history th {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    <div class="container profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <img src="https://via.placeholder.com/120" alt="Profile Picture">
            <h2><?php echo $user['name']; ?></h2>
            <p><?php echo $user['email']; ?></p>
        </div>

        <!-- User Details -->
        <div class="user-details">
            <h4>Account Details</h4>
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
            <a href="update_profile.php" class="btn btn-primary">Update Profile</a>
        </div>

        <!-- Order History (Optional) -->
        <div class="order-history">
            <h4>Order History</h4>
            <?php if (mysqli_num_rows($order_result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($order_result)): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td><?php echo $order['order_status']; ?></td>
                        <td><?php echo '$' . $order['total_amount']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No orders found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
mysqli_close($link);
?>
