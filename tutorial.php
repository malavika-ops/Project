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

$topicid = isset($_GET['topicid']) ? intval($_GET['topicid']) : 0;

$headingStmt = $conn->prepare("SELECT title FROM coursesubsubtopics WHERE topicid = ?");
$headingStmt->bind_param("i", $topicid);
$headingStmt->execute();
$headingStmt->bind_result($heading);
$headingStmt->fetch();
$headingStmt->close();

$contentStmt = $conn->prepare("SELECT content, heading FROM tutorial WHERE topicid = ?");
$contentStmt->bind_param("i", $topicid);
$contentStmt->execute();
$contentStmt->store_result();

$contentStmt->bind_result($content, $title);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="Logo.png" type="image/icon type">
    <title><?php echo htmlspecialchars($heading); ?></title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <h1><?php echo htmlspecialchars($heading); ?></h1>
    </header>

    <main>
        <?php while($contentStmt->fetch()): ?>
            <section>
                <h2 style="font-size: 25px; text-decoration:underline;"><?php echo htmlspecialchars($title); ?></h2>
                <p style="font-size: 18px;">
                    <?php echo nl2br(htmlspecialchars($content)); ?>
                </p><br><br>
            </section>
        <?php endwhile; ?>
    </main>

</body>
</html>