<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");
?>
<?php
$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");

if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
} else {
    //echo "connection successfull";
}
?>
<!-- validate username and password if match then set the session variable and set the cookie -->
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if (array_key_exists('email', $_POST) || array_key_exists('password', $_POST)) {
    $error = "";
    $success = "";


    $username = trim($_POST['username']);
    $password = trim($_POST['password']);



    //check validation for the admin user 
    if ($username == "root" && $password == "root") {
        $_SESSION['username'] = $username;
        header("Location:/E-commerce website/admin/admin_home.php");
    } else {

        $query = "SELECT * FROM e_login_table WHERE email='$username'";
        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            $hash_password = $row["password"];
            if (password_verify($password, $hash_password)) {
                $success = "<p>Login successful</p>";
                setcookie('login_id', $row['id'], time() + 24 * 60 * 60 * 365);
                $_SESSION['login_id']=$_COOKIE['login_id'];
               // echo "login id is".$_SESSION['login_id'];
                header("Location: welcome.php");
                exit();
            } else {
                // Incorrect password
                $error = "<p>Username and password do not match.</p>";
            }
        } else {

            $error = "<p>Username and password do not match.</p>";
        }
    }


    mysqli_close($link);
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            /* display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh; */
        /* margin: 0;
            background-color: #f8f9fa; 
        } */

        .company-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            background-color: #f8f9fa;
        }

        .center-wrapper {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-form-container {
            max-width: 450px;
            width: 80%;
            padding: 4rem;
            border-radius: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        .form-control {
            border: 1px solid #ced4da;
            border-radius: 0.4rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 0.4rem;
            padding: 0.6rem 1.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <!-- Company Name -->
    <div class="center-wrapper">
        <div class="company-name text-primary">EzyBuy</div>

        <!-- Login Form Section -->
        <div class="login-form-container border rounded shadow">
            <h2 class="text-center">Welcome Back!</h2>
            <p class="text-muted text-center">Please login using your email and password</p>

            <!-- Error Message -->
            <div id="error-message" class="alert alert-danger d-none" role="alert">
                Invalid username or password. Please try again.
            </div>

            <!-- Login Form -->
            <form method="POST">


                <?php if (!empty($error)) { ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php } ?>


                <?php if (!empty($success)) { ?>
                    <div class="success"><?php echo $success; ?></div>
                <?php } ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Email</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter your email" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background-color:orangered">Login</button>
            </form>

            <!-- Register Message -->
            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? <a href="register.php" class="text-primary">Register</a></p>
            </div>
        </div>
    </div>
    <!-- <script>
        const form = document.querySelector('form');
        const errorMessage = document.getElementById('error-message');

        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailRegex.test(username)) {
                errorMessage.textContent = "Please enter a valid email address.";
                errorMessage.classList.remove('d-none');
                return;
            }


            if (!passwordRegex.test(password)) {
                errorMessage.textContent = "Password must be at least 8 characters long, with one uppercase letter, one lowercase letter, one number, and one special character.";
                errorMessage.classList.remove('d-none');
                return;
            }


            errorMessage.classList.add('d-none');
            alert("Login successful");
        });
    </script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>