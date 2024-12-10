<?php
    session_start();
    session_destroy();
    header("Location: /E-commerce%20website/templates/welcome.php");
    exit();
?>