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

    $user = $_SESSION['username'];
    $subtopic_id = isset($_POST['subtopic_id']) ? intval($_POST['subtopic_id']) : 0;
    $total_questions = isset($_POST['total_questions']) ? intval($_POST['total_questions']) : 0;

    $score = 0;
    $results = [];

    foreach($_POST as $key => $value){

        if(strpos($key, 'question_') === 0){

            $question_id = intval(str_replace('question_', '', $key));
            $answer_id = intval($value);

            $stmt = $conn->prepare("SELECT a.is_correct, q.question_text, a.answer_text, (SELECT answer_text FROM answers WHERE question_id = q.id AND is_correct = 1) AS correct_answer FROM answers a JOIN questions q ON a.question_id = q.id WHERE a.id = ?");
            $stmt->bind_param("i", $answer_id);
            $stmt->execute();
            $stmt->bind_result($is_correct, $question_text, $selected_answer, $correct_answer);
            $stmt->fetch();
            $stmt->close();

            $results[] = [
            
                'question_text' => $question_text,
                'selected_answer' => $selected_answer,
                'correct_answer' => $correct_answer,
                'is_correct' => $is_correct
            ];

            if($is_correct){
                $score++;
            }

        }

    }

    $stmt = $conn->prepare("SELECT id, attempts FROM userresult WHERE username = ? AND subtopic_id = ?");
    $stmt->bind_param("si", $user, $subtopic_id);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){

        $stmt->bind_result($result_id, $attempts);
        $stmt->fetch();
        $stmt->close();

        $attempts += 1;
        $update_stmt = $conn->prepare("UPDATE userresult SET score = ?, attempts = ?, last_attempt = NOW() WHERE id = ?");
        $update_stmt->bind_param("iii", $score, $attempts, $result_id);
        $update_stmt->execute();
        $update_stmt->close();

    }
    else{

        $stmt->close();
        $insert_stmt = $conn->prepare("INSERT INTO userresult (username, subtopic_id, score, attempts, last_attempt) VALUES (?, ?, ?, 1, NOW())");
        $insert_stmt->bind_param("sii", $user, $subtopic_id, $score);
        $insert_stmt->execute();
        $insert_stmt->close();

    }

    $conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <title>Test Results</title>

    <link rel="stylesheet" href="test.css">
</head>
<body>
    <div class="results-container">

        <?php if($score == $total_questions): ?>

            <h1>Congratulations, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <?php elseif($score > $total_questions / 2): ?>

            <h1>Your good to go, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <?php else: ?>

            <h1>Oh no try again, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <?php endif; ?>

        <p>Your Score: <?php echo $score; ?> / <?php echo $total_questions; ?></p>

        <div class="achievements">

            <h2>Achievements</h2>

            <?php if($score == $total_questions): ?>
                <p>You got a perfect score!</p>

            <?php elseif($score > $total_questions / 2): ?>
                <p>Great job! You scored above average.</p>

            <?php else: ?>
                <p>Keep practicing and try again!</p>

            <?php endif; ?>

        </div>

        <a href="assessment.php">Back</a>

    </div>

    <div style="background-color:#333;max-width: 600px;margin: 0 auto;padding: 20px;margin-top:20px;margin-bottom:20px;border-radius: 8px;" class="answers-review">

            <h1>Review Your Answers</h1><br><br>
            <ul>

                <?php foreach($results as $result): ?>

                        <strong style="text-decoration:underline;">Question:</strong> <?php echo htmlspecialchars($result['question_text']); ?><br><br>
                        <strong>Your Answer:</strong> <?php echo htmlspecialchars($result['selected_answer']); ?>

                        <?php if($result['is_correct']): ?>
                            <b><span style="color: grey;">(Correct)</span></b><br><br><br><br><br>

                        <?php else: ?>
                            <b><span style="color: black;">(Wrong)</span></b><br><br>
                            <strong>Correct Answer:</strong> <?php echo htmlspecialchars($result['correct_answer']); ?><br><br><br><br><br>

                        <?php endif; ?>

                <?php endforeach; ?>

            </ul>

    </div>

</body>
</html>