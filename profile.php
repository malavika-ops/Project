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

    $username=$_SESSION['username'];
    $stmt=$conn->prepare("SELECT firstname, lastname, email FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName, $email);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="Logo.png" type="image/icon type">
        <title>Dashboard</title>
        
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <link rel="stylesheet" href="profile.css">
    </head>
    <body>

        <div class="bodyhead">

            <div class="bodyheadnavbar">

                <div class="bodyheadcontent">

                    <a class="userwelcome" id="userwelcome"><?php echo $username;?></a>
                    <div class="userdropdown" id="userdropdown">

                        <p><?php echo $firstName. " ". $lastName;?></p>
                        <a href="logout.php"><i style="margin-right:5px;text-align:center;" class='bx bx-log-out-circle'></i>Logout</a>

                    </div>

                </div>

            </div>

        </div>

        <nav class="sidemenu collapsed" id="sidemenu">

            <header>

                <div class="logocontent">

                    <span class="logo">
                        <img id="logoimg" src="Logo.png">
                    </span>

                </div>

                <i class="bx bxs-log-out togglebutton" id="togglebtn"></i>

            </header>

            <div class="sidemenubar">

                <div class="menu">

                    <ul class="menucontent">

                        <li class="menulinks">

                            <a href="dashboard.php">

                                <i class='bx bxs-dashboard menuicon'></i>
                                <span class="text menutext">Dashboard</span>

                            </a>

                        </li>

                        <li class="menulinks">

                            <a href="mytraining.php">

                                <i class='bx bxs-book-reader menuicon'></i>
                                <span class="text menutext">My Training</span>

                            </a>

                        </li>

                        <li class="menulinks">

                            <a href="assessment.php">

                                <i class='bx bxs-edit menuicon'></i>
                                <span class="text menutext">Assessment</span>

                            </a>

                        </li>

                        <li class="menulinks">

                            <a href="cyblib.php">

                                <i class='bx bx-library menuicon'></i>
                                <span class="text menutext">Cyber Library</span>

                            </a>

                        </li>

                        <li class="menulinks">

                            <a href="profile.php">

                                <i class='bx bxs-user menuicon active'></i>
                                <span class="text menutext">Profile</span>

                            </a>

                        </li>

                    </ul>

                </div>

                <div class="bottom" style="margin-top:20px;">

                    <li class="lightdarkmode">

                        <div class="lightdarkmodetoggle">

                            <span class="lightanddarkswitch"></span>

                        </div>

                    </li>

                </div>

            </div>

        </nav>

        <div class="profiletitle">
            <h1 id="title">Profile</h1>
        </div>

        <div class="profile">

            <div class="profileinfo">

                <p id="usersname"><?php echo $firstName ." " . $lastName;?></p>
                <p id="usersemail">Username: <?php echo $username; ?></p>
                <p id="usersemail">Email: <?php echo $email; ?></p>

            </div>

            <div class="profileeditbuttons">

                <button class="profilebutton" onclick="openModifyDetails()">Modify Details</button>
                <button class="profilebutton" onclick="openresetpass()">Reset Password</button>

            </div>

        </div>

        <div class="popupwindow" id="popupwindow">

            <div class="popupcontent">

                <span class="popupclose" id="popupclose"></span>
                <h2>Modify Details</h2>

                <form id="popupmodifydetails" action="modifydetails.php" method="POST">

                    <?php echo '<input type="text" class="modifyusername" id="modifyusername" name="username" value="'. $username.'" placeholder="Username"/>'; ?>
                    <?php echo '<input type="text" class="modifyfirstname" id="modifyfirstname" name="firstname" value="'. $firstName.'" placeholder="First Name"/>'; ?>
                    <?php echo '<input type="text" class="modifylastname" id="modifylastname" name="lastname" value="'. $lastName.'" placeholder="Last Name"/>'; ?>
                    <?php echo '<input type="email" class="modifyemail" id="modifyemail" name="email" value="'. $email.'" placeholder="Email"/>'; ?>

                    <button type="submit">Confirm Changes</button>

                </form>

            </div>

        </div>

        <div class="resetpasspopup" id="resetpasspopup">

            <div class="resetpasspopupcontent">

                <span class="resetpasspopupclose" id="resetpasspopupclose"></span>
                <h2>Reset Password</h2>

                <form id="resetpassdetails" action="resetpass.php" method="POST">

                    <input type="password" class="oldpass" id="oldpass" name="oldpass" placeholder="Old Password" required/>
                    <input type="password" class="newpass" id="newpass" name="newpass" placeholder="New Password" required/>
                    <input type="password" class="confirmpass" id="confirmpass" name="confirmpass" placeholder="Confirm Password" required/>

                    <button type="submit">Save Changes</button>

                </form>

            </div>

        </div>

        <script src="profile.js"></script>
        <script src="modifydetails.js"></script>
        <script src="resetpass.js"></script>

    </body>

</html>