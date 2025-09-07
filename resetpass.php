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

    $conn=new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }

    $username= $_SESSION['username'];
    $oldpass=$_POST['oldpass'];
    $newpass=$_POST['newpass'];
    $confirmpass=$_POST['confirmpass'];

    $stmt=$conn->prepare("SELECT password FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($existingpass);
    $stmt->fetch();
    $stmt->close();

    if($oldpass===$existingpass){

        if($newpass===$confirmpass){

            $stmt=$conn->prepare("UPDATE users SET password = ? WHERE name = ?");
            $stmt->bind_param("ss",$newpass,$username);

            if($stmt->execute()){

                session_destroy();
                echo "<script>
                    alert('Password changed successfully.');
                    window.location.href='loginregistration.php';
                </script>";

            }

            else{
                echo "Update failed. Try Again";
            }

            $stmt->close();

        }
        else{

            echo "<script>
                alert('New password and confirm password do not match.');
                window.location.href='profile.php';
            </script>";

        }

    }
    else{

        echo "<script>
            alert('Old password is incorrect.');
            window.location.href='profile.php';
        </script>";
        
    }

    $conn->close();

?>