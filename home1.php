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

    $popupmessage="";

    if($_SERVER["REQUEST_METHOD"]=="POST"){

        $name=$_POST['name'];
        $email=$_POST['email'];
        $message=$_POST['message'];
    
        $sql="INSERT INTO contactus (name, email, message) VALUES (?, ?, ?)";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);
    
        if($stmt->execute()===TRUE){
            $popupmessage="Message sent.";
        }
        else{
            $popupmessage="Error: ". $stmt->error;
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
        <title>Cyber Range Training Framework</title>

        <link rel="stylesheet" href="home1.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </head>
    <body>
        <div class="header">

            <div class="navbar">

                <div class="logo"><a href="home1.php"><img src="Logo1.png"></a></div>

                <ul class="menu">

                    <li class="menu1"><a class="amenu1" href="home1.php">Home</a></li>
                    <li class="menu2"><a class="amenu2" href="#aboutus">About</a></li>
                    <li class="menu1"><a class="amenu1" href="#courses">Courses</a></li>
                    <li class="menu3"><a class="amenu3" href="#contactus">Contact</a></li>

                </ul>

                <div class="dropdown" style="display: inline-block;width: 10%;">

                    <button class="dropbtn">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></button>

                    <div class="dropdown-content">

                        <a href="#">Settings</a>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="logout.php">Logout</a>

                    </div>

                </div>

                <div class="toggle_button">

                    <i class="fa-solid fa-bars"></i>

                </div>

            </div>

            <div class="mediamenu">

                <li class="menu1"><a class="amenu1" href="home1.php">Home</a></li>
                <li class="menu2"><a class="amenu2" href="#aboutus">About</a></li>
                <li class="menu1"><a class="amenu1" href="#courses">Courses</a></li>
                <li class="menu3"><a class="amenu3" href="#contactus">Contact</a></li>

                <div class="dropdown">

                    <a class="dropbtn">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></a>

                    <div class="dropdown-content">

                        <a href="profile.php">Settings</a>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="logout.php">Logout</a>

                    </div>

                </div>

            </div>

        </div>

        <section id="homecontent">

            <div class="content">

                <small class="content1">Welcome to</small>
                <h1 class="content2">CYBLEARN</h1>
                <p style="color:white;font-size:50px;margin-bottom:50px;"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <a class="content3" href="dashboard.php">Go to Dashboard</a>

            </div>

        </section>

        <section id="aboutus">

            <div class="about">

                <h3 class="about1">About</h3>

                <div class="aboutdiv">

                    <p class="about2"><b>CYBLEARN</b> offer you a controlled, simulated environment for cybersecurity training that enables users to hone their cyberattack defense techniques, test security protocols in a practical environment, and develop their abilities without endangering real-world systems.</p>
                    <img src="image.jpg">

                </div>

            </div>

        </section>

        <section id="courses">

            <div class="coursecontainer">

                <div class="courseoffered">

                    <div class="courseitems">

                        <h1>Fundamental Courses</h1>
                        <img src="background.jpg" alt="Course 1">

                    </div>

                    <div class="courseitems">

                        <h1>Intermediate Courses</h1>
                        <img src="course2.jpg" alt="Course 2">

                    </div>

                    <div class="courseitems">

                        <h1>Advanced Courses</h1>
                        <img src="course3.jpg" alt="Course 3">

                    </div>

                    <div class="courseitems">

                        <h1>Specialized Courses</h1>
                        <img src="course4.jpg" alt="Course 4">

                    </div>

                    <div class="courseitems">

                        <h1>Simulation and Practice</h1>
                        <img src="course5.jpg" alt="Course 5">

                    </div>

                </div>

                <button class="directions left">&lt;</button>
                <button class="directions right">&gt;</button>

            </div>

        </section>

        <section id="contactus">

            <div class="contact">

                <h3 class="contactus1">Contact Us</h3>

                <div class="contactdiv">

                    <form id="contactusleft" method="post" action="home.php">

                        <input type="text" name="name" placeholder="Enter Name" class="contactinfo" required><br>
                        <input type="email" name="email" placeholder="Enter Email" class="contactinfo" required><br>

                        <textarea name="message" placeholder="Enter Message" class="contactinfo" required></textarea><br>

                        <button type="submit">Submit <img class="icon" src="arrow.png" width="10px"></button>

                    </form>

                </div>

            </div>

        </section>

        <script>

            <?php
                if($message!=""):
            ?>

            alert("<?php echo $popupmessage; ?>");

            <?php
                endif;
            ?>
            
        </script>

        <script src="home.js"></script>
        <script src="course.js"></script>

    </body>
</html>