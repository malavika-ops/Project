<?php

    session_start();

    $servername="localhost";
    $username="root";
    $password="";
    $dbname="cyblearn";

    $conn=new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }

    $success="";
    $error="";

    if($_SERVER["REQUEST_METHOD"]=="POST"){

        $firstName=$_POST['firstname'];
        $lastName=$_POST['lastname'];
        $name=$_POST['name'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $confirmPassword=$_POST['confirmpassword'];

        if($password !== $confirmPassword){
            $error="Passwords do not match.";
        }
        else{

            $stmt=$conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
            $stmt->bind_param("ss", $email, $name);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows>0){
                $error="Email or Username is already in use.";
            }
            else{

                $stmt=$conn->prepare("INSERT INTO users (firstname, lastname, name, email, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $firstName, $lastName, $name, $email, $password);

                if($stmt->execute()){
                    $success="Registration successful.";
                }
                else{
                    $error = "Error: " . $stmt->error;
                }

            }

            $stmt->close();

        }

        $conn->close();

    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="Logo.png" type="image/icon type">
        <title>Registration</title>

        <link rel="stylesheet" href="register.css">
    </head>
    <body>

        <div class="header">

            <div class="navbar">

                <div class="logo"><a href="home.php"><img src="Logo1.png"></a></div>

            </div>

        </div>

        <div class="content">

            <h1>Register</h1>

            <form id="registerform" method="post">

                <input type="text" name="firstname" placeholder="First Name" class="registerinfo" required><br>
                <input type="text" name="lastname" placeholder="Last Name" class="registerinfo" required><br>
                <input type="text" name="name" placeholder="Username" class="registerinfo" required><br>
                <input type="email" name="email" placeholder="Email" class="registerinfo" required><br>
                <input type="password" name="password" placeholder="Password" class="registerinfo" required><br>
                <input type="password" name="confirmpassword" placeholder="Confirm Password" class="registerinfo" required><br>

                <button type="submit">Register <img src="arrow.png" width="10px" style="margin-left: 10px;"></button>

                <p>Already have an account ?? <a href="loginregistration.php" style="margin-left: 10px;">Login Now!!</a></p>

            </form>

        </div>

        <?php if($error != ""): ?>

            <script>
                alert("<?php echo $error; ?>");
            </script>

        <?php elseif($success != ""): ?>

            <script>
                alert("<?php echo $success; ?>");
                window.location.href = 'loginregistration.php';
            </script>

        <?php endif; ?>

    </body>
</html>