<?php
session_start();
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to sign-in page
    header("Location: /E-commerce website/templates/welcome.php");
    exit();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
$username=$_SESSION['username'];
$_SESSION['username']=$username;
include('admin_header.php');

$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Count total records
$query = "SELECT count(*) as total_records FROM e_product_details";
$result = mysqli_query($link, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($link));
} else {
    $row = mysqli_fetch_assoc($result);
    $total_records = $row["total_records"];
}

$records_per_page = 6;
$total_pages = ceil($total_records / $records_per_page);

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;


if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages) {
    $current_page = $total_pages;
}

//show 6 record per page 
$start_from = ($current_page - 1) * $records_per_page;

$query = "SELECT * FROM e_product_details LIMIT $start_from, $records_per_page";
$result = mysqli_query($link, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($link));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel-HOME</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (required for Bootstrap 5 modals and other components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <h2 class="text mx-3 mb-0">All Records <?php echo $total_records; ?></h2>
        <div class="d-flex ms-auto">
             <!-- <a href="admin_customer_details.php" class="btn btn-warning mx-2">All Registered Users</a> -->
            <a href="admin_customer_details.php" class="btn btn-warning mx-2">Customer Details</a>
            <a href="add_product.php" class="btn btn-success mx-2">Add New Item</a>
        </div>
    </div>
</div>


<!-- Table -->
<div class="table-responsive mb-5 mx-3">
    <table class="table table-bordered table-striped table-white">
        <thead class="thead-dark">
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Image</th> 
                <th>Update</th>
                <th>Delete</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            while ($row = mysqli_fetch_array($result)) {
                $image_url = $row['image_path'];
                ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo "₹ ".$row['price']; ?></td>
                    
                    <!-- Displaying the Image -->
                    <td>
                        <?php if ($image_url): ?>
                            <img src="<?php echo $image_url; ?>" alt="Product Image" style="max-width: 100px; height: auto;">
                        <?php else: ?>
                            <p>No image available</p>
                        <?php endif; ?>
                    </td>

                    <td><a href="admin_update.php?product_id=<?php echo urlencode($row['product_id']); ?>" class="btn btn-success">Update</a></td>
                    <td><a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['product_id']; ?>">Delete</a></td>
                    <td><a href="admin_view.php?product_id=<?php echo urlencode($row['product_id']); ?>" class="btn btn-primary">View</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<div class="pagination-container text-center mb-5 d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <!-- Previous button -->
            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo ($current_page - 1); ?>" aria-label="Previous">Previous</a>
            </li>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next button -->
            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo ($current_page + 1); ?>" aria-label="Next">Next</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Modal for Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <h4 class="text-danger">Are you sure you want to delete this Item?</h4>
        <p>Please confirm your decision.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="#" id="deleteRecordLink" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Handle Delete Button Click in Modal
$('#deleteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var productId = button.data('id'); 
    
    var deleteUrl = 'admin_delete.php?id=' + productId;
    
    var modal = $(this);
    modal.find('#deleteRecordLink').attr('href', deleteUrl); 
});
</script>

</body>
</html>
