<?php
include("admin_header.php");
session_start();
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to sign-in page
    header("Location: /E-commerce website/templates/welcome.php");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_GET['user_id'];
$query = "SELECT * FROM e_login_table WHERE id = '$user_id'";
$result = mysqli_query($link, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // PHP Validation
    $errors = [];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        // Update user details
        $query = "UPDATE e_login_table SET name = '$name', email = '$email' WHERE id = '$user_id'";
        if (mysqli_query($link, $query)) {
            header("Location: /E-commerce website/admin/admin_registered_user.php?update_message=User update successfully"); // Redirect back to the user list
            exit();
        } else {
            $errors[] = "Error updating record: " . mysqli_error($link);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex justify-content-start">
        <a href="admin_home.php" 
        class="btn-close" 
        aria-label="Close" 
        style="font-size: 24px; text-decoration: none;"></a>

    </div>
    <h2 class="text-center">Edit User</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger d-flex justify-content-center w-50">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<div class="container mt-4 d-flex justify-content-center">


    <!-- Display Validation Errors -->
    

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $user['name'] : $user['name']; ?>" >
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $user['email'] : $user['email']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
mysqli_close($link);
?>
