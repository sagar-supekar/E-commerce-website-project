<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('admin_header.php');

// Database connection
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Count total records
$query = "SELECT count(*) as total_records FROM order_details"; 
$result = mysqli_query($link, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($link));
} else {
    $row = mysqli_fetch_assoc($result);
    $total_records = $row["total_records"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Order Details</title>

  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="navbar">
    <div class="d-flex justify-content-start">
        <a href="admin_home.php" 
        class="btn-close" 
        aria-label="Close" 
        style="font-size: 24px; text-decoration: none;"></a>

    </div>
    <h2 class="text mx-3">Order Records <?php echo $total_records; ?></h2>
</div>

<!-- Table -->
<div class="table-responsive mb-5 mx-3">
    <table class="table table-bordered table-striped table-white">
        <thead class="thead-dark">
            <tr>
                <th>Sr No</th>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Pincode</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Payment Method</th>
                <th>Time and Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Use JOIN to fetch data from both order_details and e_login_table
            $query = "
                SELECT o.*, e.email 
                FROM order_details o
                LEFT JOIN e_login_table e ON o.user_id = e.id
            ";
            $result = mysqli_query($link, $query);
            if (!$result) {
                die("Query failed: " . mysqli_error($link));
            }
            $count = 1;
            
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mobile_number']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['pincode']; ?></td>
                    <td>
                            <img src="<?php echo $row['image_path']; ?>" alt="Product Image" style="max-width: 100px; height: auto;">
                    </td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['product_price']; ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>
