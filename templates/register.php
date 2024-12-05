<?php
//session_start();
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if(isset($_COOKIE['login_id']))
{
    header("Location:welcome.php");
    exit();
}
include("/home/web/public_html/E-commerce website/includes/header.php");
include("/home/web/public_html/E-commerce website/includes/second_header.php");

$link = mysqli_connect("localhost", "root", "root", "E_commerce_website");
if (mysqli_connect_error()) {
    die("Connection error: " . mysqli_connect_error());
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$name = $email = $password = '';
$nameErr = $emailErr = $passErr =$confpassErr= '';
$signal = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //name validation 
    if (empty($_POST["full-name"])) {
        $nameErr = "First name is required";
        $signal = false;
    } else {
        $first_name = test_input($_POST["full-name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
            $nameErr = "Only letters and white space allowed";
            $signal = false;
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $signal = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $signal = false;
        } else {
            // Check if the email is already registered
            $query = "SELECT id FROM e_login_table WHERE email='$email'";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                $emailErr = "The email address is already registered.";
                $signal = false;
            }
        }
    }

    //password validation
    if (empty($_POST["password"])) {
        $passErr = "Password is required";
        $signal = false;
    } else {
        $password = test_input($_POST["password"]);
        $password_pattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

        if (!preg_match($password_pattern, $password)) {
            $passErr .= "Password must have at least 8 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character.\n";
            $signal = false;
        }
    }
    if(($_POST["password"])!=($_POST["confirm-password"]))
    {
        $confpassErr = "Password should match";
        $signal = false;
    }

    if ($signal) {
        $full_name = mysqli_real_escape_string($link, $_POST['full-name']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $password = mysqli_real_escape_string($link, $_POST['password']);

        //secure the password
        $secure_pass = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO e_login_table (name, email, password) VALUES ('$full_name', '$email', '$secure_pass')";
        $result = mysqli_query($link, $query);

        $query = "SELECT id FROM e_login_table WHERE email='$email'";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) > 0) {
            $register_id_query = mysqli_fetch_assoc($result);
            $register_id = $register_id_query['id'];
            $_SESSION['register_id'] = $register_id;
            setcookie('register_id', $register_id, time() + 24 * 60 * 60 * 365);
            header("Location: register_success_mail.php?email=" . urlencode($email)."&name=".urlencode($full_name));
            exit();
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <style>
      
        .registration-page {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh; 
        }

        .registration-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            width: 400px;
            height: 490px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .company-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 30px;
            
            color: #007BFF;
        }

        .form-header {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .input-field {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }

        button {
            width: 93%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            font-size: 14px;
            color: #888;
        }

        .text-primary {
            color: #007BFF;
        }
    </style>
</head>

<body>

    <div class="registration-page">
        <div class="company-name">EzyBuy</div>
        <div class="registration-container">
            <form id="registerForm" method="POST">
                <h2 class="form-header d-flex justify-content-content">Register</h2>
                <div>
                    <input type="text" id="name" placeholder="Full Name" name="full-name" class="input-field">
                    <div id="nameError" class="error"><?php echo $nameErr; ?></div>
                </div>
                <div>
                    <input type="email" id="email" class="input-field" placeholder="Email" name="email">
                    <div id="emailError" class="error"><?php echo $emailErr; ?></div>
                </div>
                <div>
                    <input type="password" id="password" placeholder="Password" name="password" class="input-field">
                    <div id="passwordError" class="error"><?php echo $passErr; ?></div>
                </div>
                <div>
                    <input type="password" id="password" placeholder="Confirm Password" name="confirm-password" class="input-field">
                    <div id="passwordError" class="error"><?php echo $confpassErr; ?></div>
                </div>
                <button type="submit">Register</button>
                <div id="successMessage" class="success"></div>

                <div class="text-center">
                    <p class="text-muted">Already have an account? Please <a href="login.php" class="text-primary">Sign in</a></p>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
<?php
include("/home/web/public_html/E-commerce website/includes/footer.php");
?>