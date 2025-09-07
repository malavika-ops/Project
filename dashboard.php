<?php

    session_start();

    if(!isset($_SESSION['username'])){
        header("Location: loginregistration.php");
        exit();
    }

    $servername = "localhost";
    $dbusername = "root";
    $password = "";
    $dbname = "cyblearn";

    $conn = new mysqli($servername, $dbusername, $password, $dbname);

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

    $result_stmt = $conn->prepare("SELECT subtopic_id, score, attempts FROM userresult WHERE username = ?");
    $result_stmt->bind_param("s", $username);
    $result_stmt->execute();
    $result = $result_stmt->get_result();

    $subtopics = [];
    $scores = [];
    $attempts = [];

    while($row = $result->fetch_assoc()){
        $subtopics[] = 'Subtopic ' . $row['subtopic_id'];
        $scores[] = $row['score'];
        $attempts[] = $row['attempts'];
    }

    $result_stmt->close();


    $bulletin_stmt = $conn->prepare("SELECT title, description, dateandtime FROM news ORDER BY dateandtime DESC LIMIT 5");
    $bulletin_stmt->execute();
    $bulletin_result = $bulletin_stmt->get_result();

    $bulletin_items = [];

    while($row = $bulletin_result->fetch_assoc()){
        $bulletin_items[] = $row;
    }

    $bulletin_stmt->close();

    $learned_stmt = $conn->prepare("SELECT detail FROM learned WHERE username = ? ORDER BY id DESC");
    $learned_stmt->bind_param("s", $username);
    $learned_stmt->execute();
    $learned_result = $learned_stmt->get_result();

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

    <link rel="stylesheet" href="dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

    <div class="bodyhead">

        <div class="bodyheadnavbar">

            <div class="bodyheadcontent">

                <a class="userwelcome" id="userwelcome"><?php echo htmlspecialchars($username); ?></a>

                <div class="userdropdown" id="userdropdown">

                    <p><?php echo htmlspecialchars($firstName . " " . $lastName); ?></p>
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

                        <a href="#">

                            <i class='bx bxs-dashboard menuicon active'></i>
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

    <div class="dashboardtitle">
        <h1>Dashboard</h1>
    </div>


    <div class="chart">

        <div class="pie" style="padding-top:100px;padding-left: 80px;padding-right: 80px;padding-bottom: 50px;">

            <h2>Marks Obtained:</h2>
            <canvas id="userResultsChart"></canvas>

        </div>

        <div class="learned-section">

            <h2>What I Learned Today</h2>

            <form id="learnedForm" method="POST" action="savelearned.php">

                <textarea name="learnedText" id="learnedText" placeholder="What have you learned today..." required></textarea>
                <button type="submit" id="addLearnedButton">Add</button>

            </form>

            <div id="learnedEntries">

                <h3>Things You've Learned:</h3>

                <ul>

                    <?php

                        if ($learned_result->num_rows > 0){
                            while($row = $learned_result->fetch_assoc()){
                                echo "<li>" . htmlspecialchars($row['detail']) . "</li>";
                            }
                        }
                        else{
                            echo "<li>No entries yet.</li>";
                        }

                        $learned_stmt->close();

                    ?>

                </ul>

            </div>

        </div>

        <div class="newsdiv" style="padding-top:100px;padding-left: 80px;padding-right: 80px;padding-bottom: 50px;">

            <h2 style="padding-bottom:50px;">What's News</h2>

            <?php foreach($bulletin_items as $item): ?>

            <div class="newsitems">

                <h3 style="padding-bottom:10px;text-decoration:underline;"><?php echo htmlspecialchars($item['title']); ?></h3>
                <p style="padding-bottom:10px;"><?php echo htmlspecialchars($item['description']); ?></p>
                <small>Posted on: <?php echo date("F j, Y, g:i a", strtotime($item['dateandtime'])); ?></small>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

    <script src="dashboard.js"></script>

    <script>

        const subtopics = <?php
            echo '["' . implode('","', $subtopics) . '"]';
        ?>;

        const scores = <?php
            echo '[' . implode(',', $scores) . ']';
        ?>;

        const ctx = document.getElementById('userResultsChart').getContext('2d');

        const chartData = {
            labels: subtopics,
            datasets: [{
                label: 'Scores',
                data: scores,
                backgroundColor: [
                    'rgba(0, 0, 30)',
                    'rgba(0, 0, 60)',
                    'rgba(0, 0, 90)',
                    'rgba(0, 0, 120)',
                    'rgba(0, 0, 150)',
                    'rgba(0, 0, 180)'
                ],
                borderColor: [
                    'rgba(255, 255, 255)',
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += Math.round(context.raw * 100) / 100;
                                return label;
                            }
                        }
                    }
                }
            },
        };

        const userResultsChart = new Chart(ctx, config);

    </script>

</body>
</html>

<?php
    $conn->close();
?>