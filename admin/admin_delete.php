<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_GET["id"]))
{
    $id = $_GET["id"];
   
    $query="delete from e_product_details where product_id='$id'";

    $result = mysqli_query($link,$query);

    if(!$result)
    {
          die("connection failed");
    }
    else
    {
        header("Location: admin_home.php?delete_message=" . urlencode('One row deleted successfully'));
    }
}
?>