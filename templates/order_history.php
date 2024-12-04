<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if(!isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}

include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
$user_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;

if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
} 

$message = isset($_GET['message']) ? $_GET['message'] : "";

// Fetch order details for the logged-in user
$query = "SELECT * FROM order_details WHERE user_id = '$user_id' ORDER BY order_id DESC";
$result = mysqli_query($link, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-item {
            display: flex;
           
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            align-items: center;
        }
        .order-item img {
            max-width: 150px;
            margin-right: 20px;
            border-radius: 5px;
        }
        .order-item-details {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
        }
        .order-item-details > div {
            margin-right: 20px;
            margin-bottom: 10px;
        }
        .order-item-details h5 {
            margin: 0;
        }
        .order-item-details p {
            margin: 0;
            font-size: 14px;
        }
        .order-item-details .label {
            font-weight: bold;
        }
        
        .order-number {
            font-size: 18px;
            font-weight: bold;
            margin-right: 15px;
            text-align: center;
        }
      .btn-edit{
        text-decoration: none;
        width:100%;
        color:black;
        border:1px solid black;
        padding:5px;
        border-radius: 5px;
     
      }
      .btn-cancel{
        margin-left:10px;
        text-decoration: none;
        width:100%;
        color:black;
        border:1px solid black;
        padding:5px;
        border-radius: 5px;
       
      }

    </style>
</head>
<body>
    <div class="container my-5">
    <div class="text-center" style="color:green;"> <?php echo htmlspecialchars($message); ?></div>
        <h2 class="text-center">Order History</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $count=1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="order-item">
                    <!-- Product Image -->
                    <img src="/E-commerce website/admin/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product Image" style="height:105px;width:105px;">
                    <div class="order-item-details">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:150px;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td style="width:150px;"><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td style="width:150px;">₹<?php echo number_format($row['product_price'], 2); ?></td>
                <td style="width:150px;">₹<?php echo number_format($row['product_price'] * htmlspecialchars($row['quantity']), 2); ?></td>
                <td style="width:150px;padding-top:10px;">
                    <div>    
                    
                        <a href="/E-commerce website/templates/edit_order.php?order_id=<?php echo $row['order_id']; ?>" class="btn-edit">Edit</a>
                        <a href="/E-commerce website/templates/delete_order.php?user_id=<?php echo $user_id; ?>&product_id=<?php echo $row['product_id']; ?>&remark=delete_order" class="btn-cancel" onclick="return confirm('Are you sure you want to delete this order?')">Cancel Order</a>
                    </div>
                </td>

            </tr>
        </tbody>
    </table>
</div>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No orders found.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>