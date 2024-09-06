<?php
    $name = $_POST['name'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost','root','','coffee');
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        $stmt = $conn->prepare("insert into userlogin(username, password)
        values(?, ?)");
        $stmt->bind_param("ss",$name, $password);
        $stmt->execute();
        echo "Registered Successfully";
        $stmt->close();
        $conn->close();
    }