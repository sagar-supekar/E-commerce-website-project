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

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Delete user from database
    $query = "DELETE FROM e_login_table WHERE id = '$user_id'";
    if (mysqli_query($link, $query)) {
        header("Location: /E-commerce website/admin/admin_registered_user.php?delete_message=User deleted successfully"); // Redirect back to the user list
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($link);
    }
}

mysqli_close($link);
?>
