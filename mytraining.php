<?php

    session_start();

    if(!isset($_SESSION['username'])){
        header("Location: loginregistration.php");
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cyblearn";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);
    $stmt->fetch();
    $stmt->close();

    $courseQuery = "SELECT courseid, coursetitle, courseimg FROM coursethought";
    $courseResult = $conn->query($courseQuery);

    $courses = [];

    if($courseResult->num_rows > 0){
        while($row = $courseResult->fetch_assoc()){
            $courses[] = $row;
        }
    }

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

    <link rel="stylesheet" href="mytraining.css">
</head>
<body>

    <div class="bodyhead">

        <div class="bodyheadnavbar">

            <div class="bodyheadcontent">

                <a class="userwelcome" id="userwelcome"><?php echo $username; ?></a>

                <div class="userdropdown" id="userdropdown">

                    <p><?php echo $firstName . " " . $lastName; ?></p>
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

                    <li class="menulinks active">

                        <a href="dashboard.php">

                            <i class='bx bxs-dashboard menuicon'></i>
                            <span class="text menutext">Dashboard</span>

                        </a>

                    </li>

                    <li class="menulinks">

                        <a href="mytraining.php">

                            <i class='bx bxs-book-reader menuicon active'></i>
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

                            <i class='bx bxs-user menuicon'></i>
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

    <div class="coursetitle">
        <h1>Courses</h1>
    </div>

    <div class="courses">

        <?php foreach($courses as $course): ?>

        <div class="courseitems">

            <img src="<?php echo htmlspecialchars($course['courseimg']); ?>">
            <h2><?php echo htmlspecialchars($course['coursetitle']); ?></h2>
            <a href="coursesubtopics.php?courseid=<?php echo htmlspecialchars($course['courseid']); ?>">Start Training</a>

        </div>

        <?php endforeach; ?>

    </div>

    <script src="mytraining.js"></script>

</body>
</html>