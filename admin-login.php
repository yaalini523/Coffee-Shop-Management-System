<?php
    $name = $_POST['name'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost','root','','coffee');
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        $stmt = $conn->prepare("select * from adminlogin where username=? and password=?");
        $stmt->bind_param("ss",$name, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            header("Location: /coffee/admin.php");
            exit();
        }
        else{
            echo "Login Failed";
        }
        $stmt->close();
        $conn->close();
    }