<?php
session_start();
if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
}

for ($i = 1; $i <= $num_users; $i++) {
    if (!isset($_SESSION['user' . $i]) || $_SESSION['user' . $i] === '') {
        $_SESSION['game_error'] = "Error: not all users are signed in. Please log in all players before starting the game.";
        header("Location: login.php");
        exit();
    } else {
        // initiate point session var
        $_SESSION['user' . $i . '_points'] = 0;
    }
}

if (!isset($_SESSION['user_turn'])) {
    $_SESSION['user_turn'] = 1; // start with user 1
}

if (!isset($_SESSION['curr_question'])) {
    $_SESSION['curr_question'] = null; // no question selected yet
}

if (!isset($_SESSION['prev_questions'])) {
    $_SESSION['prev_questions'] = array(); // track used questions
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jeopardy</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1 class="game">Jeopardy</h1>
    </header>
    <main>
        <?php
        if (isset($_SESSION['game_error'])) {
            echo "<p class='error'>" . $_SESSION['game_error'] . "</p>";
            unset($_SESSION['game_error']);
        }
        ?>
        <div>
            <div class="scoreboard">
                <ul>
                    <li>User 1: [username], [points] Points</li>
                </ul>
            </div>
            <div class="turn">
                <h2>It is [username]'s turn</h2>
            </div>
        </div>
        <div class="category-column">
            <div class="question">200</div>
            <div class="question">400</div>
            <div class="question">600</div>
            <div class="question">800</div>
            <div class="question">1000</div>
        </div>
        <form class="answer-box" method="post">
            <label for="answer">Answer: </label>
            <input type="text" name="answer" id="answer" placeholder="What is a pigeon?" required>
            <button type="submit">Guess</button>
        </form>
        <div class="timer">## sec</div>
    </main>
</body>

</html>