<?php
        $link=mysqli_connect("localhost","root","root","E_commerce_website");

        if (mysqli_connect_error()) {
            die("Connection error: " . mysqli_connect_error()); 
        }
        else{
            echo "connection successfull";
        }

?>