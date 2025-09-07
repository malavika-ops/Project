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

    $courseid = isset($_GET['courseid']) ? intval($_GET['courseid']) : 0;


    $courseStmt = $conn->prepare("SELECT coursetitle FROM coursethought WHERE courseid = ?");
    $courseStmt->bind_param("i", $courseid);
    $courseStmt->execute();
    $courseStmt->bind_result($coursetitle);
    $courseStmt->fetch();
    $courseStmt->close();


    $subtopicStmt = $conn->prepare("SELECT subtopicid, title FROM coursesubtopics WHERE courseid = ?");
    $subtopicStmt->bind_param("i", $courseid);
    $subtopicStmt->execute();
    $subtopicStmt->bind_result($subtopicid, $title);
    $subtopics = [];

    while($subtopicStmt->fetch()){
        $subtopics[] = ['subtopicid' => $subtopicid, 'title' => $title];
    }

    $subtopicStmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Logo.png" type="image/icon type">
    <title><?php echo htmlspecialchars($coursetitle); ?></title>

    <link rel="stylesheet" href="iocs.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="head">

        <a href="mytraining.php"><i class='bx bxs-log-out'></i>Back</a>

        <div style="display:flex;align-items:center;justify-content:center;color:white;" class="title">

            <h1><?php echo htmlspecialchars($coursetitle); ?></h1>

        </div>

    </div>

    <div class="row">

        <?php foreach($subtopics as $subtopic): ?>

        <div class="column">

            <h3><i class='bx bx-book-open' style="padding-right: 10px;"></i><?php echo htmlspecialchars($subtopic['title']); ?></h3>
            <a href="subsubtopics.php?subtopicid=<?php echo htmlspecialchars($subtopic['subtopicid']); ?>&courseid=<?php echo htmlspecialchars($_GET['courseid']); ?>">Start</a>

        </div>

        <?php endforeach; ?>

    </div>
    
</body>
</html>