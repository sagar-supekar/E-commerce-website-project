<?php
session_start();
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}

$user_id = isset($_COOKIE['login_id']) ? $_COOKIE['login_id'] : null;
$username = '';

if ($user_id) {
    $query = "SELECT * FROM e_login_table WHERE id='$user_id'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row["name"];
    }
}
?>
<?php
if (isset($user_id)) {
    $link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
    if ($link) {

        $query = "SELECT COUNT(*) AS cart_count FROM cart_details WHERE user_id = '$user_id'";
        $result = mysqli_query($link, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $cart_count = $row['cart_count'];
        } else {
            $cart_count = 0;
        }
    }
    mysqli_close($link);
} else {
    $cart_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .navbar {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            position: sticky;
            top: 0;
            width: 100%;
            background-color: #343a40;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #212529;
        }


        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .navbar-toggler-icon {
            background-color: white;
        }

        .dropdown-menu {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            visibility: hidden;
            position: absolute;
            z-index: 1050;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            z-index: 1050px;
        }

        /* Show the dropdown on hover */
        .dropdown:hover .dropdown-menu {
            display: block;
            visibility: visible;
            opacity: 1;
            z-index: 1050px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="/E-commerce website/templates/welcome.php">EzyBuy</a>


            <form class="d-flex ms-auto justify-content-center">
                <!-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button> -->
            </form>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center">

                    <a class="nav-link d-flex align-items-center me-3" href="<?php echo $user_id ? '/E-commerce website/templates/show_cart_items.php?user_id='.$user_id : '/E-commerce website/templates/login.php'; ?>" style="position: relative;">
                        <i class="bi bi-cart me-1"></i>
                        <!-- Badge for cart count -->
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.75rem; padding: 0.25em 0.5em;">
                            <?php echo $cart_count > 0 ? $cart_count : 0; ?>
                        </span>
                        Cart
                    </a>

                    <!-- User Name (Visible only if logged in) -->
                    <?php if ($username): ?>
                        <span id="user-name" class="nav-link " style="padding-bottom:1px; padding-right:2px;"><i class="bi bi-person mx-2 "></i><?php echo $username; ?></span>
                    <?php endif; ?>

                    <!-- Login Button (Visible only if not logged in) -->
                    <?php if (!$username): ?>
                        <a id="login-btn" class="btn btn-primary d-flex align-items-center" href="/E-commerce website/templates/login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    <?php endif; ?>


                    <?php if ($username): ?>
                        <div class="dropdown mx-3">
                            <button class="btn btn-secondary dropdown-toggle d-flex align-items-center" type="button" id="profileDropdown">
                                <i class="bi bi-person me-2"></i> Profile
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="#">My Profile</a></li>
                                <li><a class="dropdown-item" href="#">Order History</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>