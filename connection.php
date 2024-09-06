<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "coffee";
    $conn = new mysqli($servername, $username, $password, $db, 3307);
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    echo "ok";
?>