<?php

    session_start();

    if(!isset($_SESSION['username'])){
        header("Location: loginregistration.php");
        exit();
    }

    $servername="localhost";
    $username="root";
    $password="";
    $dbname="cyblearn";

    $conn=new mysqli($servername,$username,$password,$dbname);

    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }

    $currentUsername=$_SESSION['username'];
    $newUsername=$_POST['username'];
    $firstName=$_POST['firstname'];
    $lastName=$_POST['lastname'];
    $email=$_POST['email'];

    $stmt=$conn->prepare("UPDATE users SET name = ?, firstname = ?, lastname = ?, email = ? WHERE name = ?");
    $stmt->bind_param("sssss", $newUsername, $firstName, $lastName, $email, $currentUsername);

    if($stmt->execute()){
        $_SESSION['username']=$newUsername;
        header("Location: profile.php");
    }
    else{
        echo "Update failed. Try Again";
    }

    $stmt->close();
    $conn->close();
    
?>