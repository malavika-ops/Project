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

$subtopicid = isset($_GET['subtopicid']) ? intval($_GET['subtopicid']) : 0;

$subtopicStmt = $conn->prepare("SELECT title FROM coursesubtopics WHERE subtopicid = ?");
$subtopicStmt->bind_param("i", $subtopicid);
$subtopicStmt->execute();
$subtopicStmt->bind_result($subtopictitle);
$subtopicStmt->fetch();
$subtopicStmt->close();

$subsubtopicStmt = $conn->prepare("SELECT topicid, title FROM coursesubsubtopics WHERE subtopicid = ?");
$subsubtopicStmt->bind_param("i", $subtopicid);
$subsubtopicStmt->execute();
$subsubtopicStmt->store_result();

$subsubtopics = [];
$subsubtopicStmt->bind_result($topicid, $title);

while($subsubtopicStmt->fetch()){
    $tutorialStmt = $conn->prepare("SELECT content FROM tutorial WHERE topicid = ?");
    $tutorialStmt->bind_param("i", $topicid);
    $tutorialStmt->execute();
    $tutorialStmt->bind_result($content);
    $tutorialStmt->fetch();
    $tutorialStmt->close();

    $videotutorialStmt = $conn->prepare("SELECT videourl FROM videotutorial WHERE topicid = ?");
    $videotutorialStmt->bind_param("i", $topicid);
    $videotutorialStmt->execute();
    $videotutorialStmt->bind_result($videourl);
    $videotutorialStmt->fetch();
    $videotutorialStmt->close();

    $subsubtopics[] = [
        'topicid' => $topicid,
        'title' => $title,
        'content' => $content,
        'videourl' => $videourl
    ];
}

$subsubtopicStmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Logo.png" type="image/icon type">
    <title><?php echo htmlspecialchars($subtopictitle); ?></title>

    <link rel="stylesheet" href="subsubtopics.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="head">
        <a href="coursesubtopics.php?courseid=<?php echo htmlspecialchars($_GET['courseid']); ?>"><i class='bx bxs-log-out'></i>Back</a>
    </div>

    <div class="row">

        <?php foreach($subsubtopics as $subsubtopic): ?>

        <div class="column">

            <h3><i class='bx bx-book-open' style="padding-right: 10px;"></i><?php echo htmlspecialchars($subsubtopic['title']); ?></h3>
            <div class="links-container">

                <?php if(!empty($subsubtopic['videourl'])): ?>
                    <a id="watch" href="videotutorial.php?topicid=<?php echo htmlspecialchars($subsubtopic['topicid']); ?>">Watch</a>
                <?php endif; ?>

                <?php if(!empty($subsubtopic['content'])): ?>
                    <a id="read" href="tutorial.php?topicid=<?php echo htmlspecialchars($subsubtopic['topicid']); ?>">Read</a>
                <?php endif; ?>

            </div>

        </div>

        <?php endforeach; ?>

    </div>
    
</body>
</html>