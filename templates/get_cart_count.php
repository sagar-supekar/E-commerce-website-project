<?php
session_start();
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}

$user_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;

if ($user_id) {
    $query = "SELECT COUNT(*) AS cart_count FROM cart_details WHERE user_id = '$user_id'";
    $result = mysqli_query($link, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo $row['cart_count']; 
    } else {
        echo 0; // Default to 0 if query fails
    }
}

mysqli_close($link);
