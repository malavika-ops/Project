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
        die("Connection failed: ". $conn->connect_error);
    }


    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $assessment_id = intval($_GET['id']);
    }
    else{
        header("Location: assessment.php");
        exit();
    }


    $stmt = $conn->prepare("SELECT title FROM assessments WHERE id = ?");
    $stmt->bind_param("i", $assessment_id);
    $stmt->execute();
    $stmt->bind_result($title);
    $stmt->fetch();
    $stmt->close();


    $subtopics_stmt = $conn->prepare("SELECT id, name FROM assessmentsubtopics WHERE assessment_id = ?");
    $subtopics_stmt->bind_param("i", $assessment_id);
    $subtopics_stmt->execute();
    $subtopics_stmt->bind_result($subtopic_id, $subtopic_name);


    $subtopics_list = [];

    while($subtopics_stmt->fetch()){
        $subtopics_list[] = ['id' => $subtopic_id, 'name' => $subtopic_name];
    }

    $subtopics_stmt->close();

    $conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Logo.png" type="image/icon type">
    <title><?php echo htmlspecialchars($title); ?></title>

    <link rel="stylesheet" href="cra.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="bonsaia">

        <a href="assessment.php"><i class='bx bxs-log-out'></i>Back</a>

        <div style="display:flex;justify-content:center;align-items:center;color:white;" class="bonsaiatitle">

            <h1><?php echo htmlspecialchars($title); ?></h1>

        </div>

        <div class="row">

            <?php foreach($subtopics_list as $subtopic): ?>

            <div class="column">

                <h3><i class='bx bx-book-open' style="padding-right: 10px;"></i><?php echo htmlspecialchars($subtopic['name']); ?></h3>
                <a href="test.php?subtopic_id=<?php echo htmlspecialchars($subtopic['id']); ?>">Start Test</a>

            </div>

            <?php endforeach; ?>

        </div>

    </div>
    
</body>
</html>