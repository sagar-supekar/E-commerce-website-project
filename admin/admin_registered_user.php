<?php
include("admin_header.php");
session_start();
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to sign-in page
    header("Location: /E-commerce website/templates/welcome.php");
    exit();
}
if (isset($_GET['update_message'])) {
    $msg = htmlspecialchars($_GET['update_message']);
    echo "<div class='alert-container'>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            " . $msg . "
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
      </div>";
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
$query = "SELECT count(*) as total_records FROM e_login_table"; 
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
// Database connection

$start_from = ($current_page - 1) * $records_per_page;
// Fetch all registered users
$query = "SELECT * FROM e_login_table LIMIT $start_from, $records_per_page"; 
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
    <title>All Registered Users</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
    }
    </style>
</head>

<body>



<div class="container mt-4">
<div class="d-flex justify-content-start">
        <a href="admin_home.php" 
        class="btn-close" 
        aria-label="Close" 
        style="font-size: 24px; text-decoration: none;"></a>

    </div>
    <h2>All Registered Users</h2>
    
    <!-- User Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php
    // Check if there are any users
    //$user_id='';
    if (mysqli_num_rows($result) > 0) {
        $count = isset($_GET['page']) ? (($_GET['page'] - 1) * 6) + 1 : 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['id']; // Store user_id for each row
    ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <!-- Edit button with user_id in URL -->
                    <a href="edit_register_user.php?user_id=<?php echo urlencode($user_id); ?>" class="btn btn-warning" alert()>Edit</a>
                    <a href="delete_register_user.php?user_id=<?php echo urlencode($user_id); ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger">Delete</a>
                </td>
            </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No users found.</td></tr>";
    }
    ?>
</tbody>

        </table>
    </div>
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
<!-- Bootstrap JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php

mysqli_close($link);
?>
