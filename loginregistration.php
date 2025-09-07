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
    $message="";

    if($_SERVER["REQUEST_METHOD"]=="POST"){

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows>0){

            $stmt->bind_result($name, $storedPassword);
            $stmt->fetch();

            if($password===$storedPassword){
                $_SESSION['username'] = $name;
                $success="Login successful. Welcome!";
            }
            else{
                $message="Invalid Credentials.";
            }

        }
        else{
            $message="Not a User.";
        }

        $stmt->close();
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
        <title>Login</title>

        <link rel="stylesheet" href="loginregistration.css">
    </head>
    <body>

        <div class="header">

            <div class="navbar">

                <div class="logo"><a href="home.php"><img src="Logo1.png"></a></div>

            </div>

        </div>

        <div class="content">

            <h1 class="logintitle">Login</h1>

            <?php

                if($message!=""){
                    echo'<p style="color:grey;">'. $message .'</p>';
                }

            ?>

                <form id="loginform" method="POST">

                    <input type="email" name="email" placeholder="Email" class="logininfo" required><br>
                    <input type="password" name="password" placeholder="Password" class="logininfo" required><br>
                    <button type="submit">Login Now<img src="arrow.png" width="10px" style="margin-left: 10px;"></button>
                    <p>New to CYBLEARN? <a style="margin-left: 10px;" href="register.php">Register Now!!</a></p>

                </form>

        </div>

        <?php if($error != ""): ?>

            <script>
                alert("<?php echo $error; ?>");
            </script>

        <?php elseif($success != ""): ?>

            <script>
                alert("<?php echo $success; ?>");
                window.location.href = 'home1.php';
            </script>
            
        <?php endif; ?>

    </body>
</html>