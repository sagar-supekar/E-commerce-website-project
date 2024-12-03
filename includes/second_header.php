<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Navbar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        .navbar-nav .nav-item {
            margin: 0 0px; 
        }
        
        
        .nav-item:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }

      
        .dropdown-menu {
            background-color: #343a40; 
        }

        .dropdown-item:hover {
            background-color: #495057; 
            color: #fff;
            
        }

        .nav-link {
            color: #fff !important; 
        }

        .dropdown-item {
            color: #fff; 
        }
       #c-button{
        cursor: pointer;
       }
    </style>
</head>
<body>
    <nav class="navbar sticky-top navbar-expand-lg " style="background-color: #59596d;color:black; z-index: 999;">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-center " id="navbarNav">
                <ul class="navbar-nav m-auto">
                    <!-- Mobile Dropdown -->
                    <li class="nav-item dropdown">
                        <p class="nav-link dropdown-toggle" id="c-button">
                            Mobile
                        </p>
                        <ul class="dropdown-menu" aria-labelledby="mobileDropdown">
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/keypad.php">Keypad</a></li>
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/touchpad.php">Touch Pad</a></li>
                        </ul>
                    </li>
                    
                    <!-- Electronics Dropdown -->
                    <li class="nav-item dropdown">
                        <p class="nav-link dropdown-toggle"id="c-button">
                            Electronics
                        </p>
                        <ul class="dropdown-menu" aria-labelledby="electronicsDropdown">
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/laptop.php">Laptop</a></li>
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/smartwatches.php">Smart Watches</a></li>
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/earbud.php">Earbuds</a></li>
                        </ul>
                    </li>

                    <!-- Appliances Dropdown -->
                    <li class="nav-item dropdown">
                        <p class="nav-link dropdown-toggle"id="c-button">
                            Appliances
                        </p>
                        <ul class="dropdown-menu" aria-labelledby="appliancesDropdown">
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/washing_machine.php">Washing Machine</a></li>
                            <li><a class="dropdown-item" href="/E-commerce website/templates/category/refregirator.php">Refrigerator</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
