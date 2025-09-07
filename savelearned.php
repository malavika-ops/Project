<?php

    session_start();

    if(!isset($_SESSION['username'])){
        header("Location: loginregistration.php");
        exit();
    }

    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $dbname = "cyblearn";

    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $learnedText = trim($_POST['learnedText']);
        $username = $_SESSION['username'];

        if(!empty($learnedText)){

            $stmt = $conn->prepare("INSERT INTO learned (detail, username) VALUES (?, ?)");
            $stmt->bind_param("ss", $learnedText, $username);

            if($stmt->execute()){
                header("Location: dashboard.php");
                exit();
            }
            else{
                echo "Error: " . $stmt->error;
            }

            $stmt->close();

        }

    }

    $conn->close();
    
?>