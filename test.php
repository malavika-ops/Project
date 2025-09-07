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

    if(isset($_GET['subtopic_id']) && is_numeric($_GET['subtopic_id'])){
        $subtopic_id = intval($_GET['subtopic_id']);
    }
    else{
        header("Location: assessment.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT name FROM assessmentsubtopics WHERE id = ?");
    $stmt->bind_param("i", $subtopic_id);
    $stmt->execute();
    $stmt->bind_result($subtopic_name);
    $stmt->fetch();
    $stmt->close();

    $questions = [];
    $questions_stmt = $conn->prepare("SELECT q.id AS question_id, q.question_text, a.id AS answer_id, a.answer_text, a.is_correct FROM questions q JOIN answers a ON q.id = a.question_id WHERE q.assessmentsubtopic_id = ? ORDER BY q.id");
    $questions_stmt->bind_param("i", $subtopic_id);
    $questions_stmt->execute();
    $questions_stmt->bind_result($question_id, $question_text, $answer_id, $answer_text, $is_correct);

    $current_question_id = null;

    while($questions_stmt->fetch()){

        if($current_question_id !== $question_id){
            $current_question_id = $question_id;
            $questions[] = ['question_id' => $question_id, 'question_text' => $question_text, 'answers' => []];
        }

        $questions[count($questions) - 1]['answers'][] = ['answer_id' => $answer_id, 'answer_text' => $answer_text, 'is_correct' => $is_correct];

    }

    $questions_stmt->close();
    $conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="Logo.png" type="image/icon type">
    <title>Quiz Game</title>

    <link rel="stylesheet" href="test.css">
</head>
<body>

    <div class="container">

        <div class="game-header">

            <h1><?php echo htmlspecialchars($subtopic_name); ?></h1>

            <div id="timer">Time Left: <span id="time">300</span>s</div>

        </div>

        <form id="quizForm" method="POST" action="submit_test.php">

            <input type="hidden" name="subtopic_id" value="<?php echo $subtopic_id; ?>">
            <input type="hidden" name="total_questions" value="<?php echo count($questions); ?>">

            <?php foreach($questions as $question): ?>

            <div class="question-block">

                <h3><?php echo $question['question_text']; ?></h3>

                <?php foreach($question['answers'] as $answer): ?>

                    <label class="answer-option">
                        <input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo $answer['answer_id']; ?>">
                        <?php echo $answer['answer_text']; ?>
                    </label><br>

                <?php endforeach; ?>

            </div>

            <?php endforeach; ?>

            <button type="button" onclick="submitQuiz()">Submit</button>

        </form>

    </div>

    <script>

        let timeLeft = 300;
        const timerElement = document.getElementById('time');

        function countdown(){
            if(timeLeft <= 0){
                submitQuiz();
            }
            else{
            timeLeft--;
            timerElement.textContent = timeLeft;
            }
        }

        setInterval(countdown, 1000);

        function submitQuiz() {
            document.getElementById('quizForm').submit();
        }

    </script>
</body>
</html>