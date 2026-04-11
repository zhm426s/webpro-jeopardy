<?php
session_start();

if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
    }

// ensure all users are logged in, and get/initiate points if so
$scoreboard = '';
for ($i = 1; $i <= $num_users; $i++){
    if (!isset($_SESSION['user'.$i]) || $_SESSION['user'.$i] === ''){
        // TODO validation: display error message saying that not all users are signed in
        echo "Error: not all users signed in"; // for testing only
        exit;
    } else {
        if (!isset($_SESSION['user'.$i.'_points'])){
            // initiate point session var
            $_SESSION['user'.$i.'_points'] = 0;
        }
        $scoreboard = $scoreboard . "<li>" . $_SESSION['user'.$i] . ": " . $_SESSION['user'.$i.'_points'] . " points</li>";
    }
}

// get user_turn if it is set, else set to zero for new game
if (isset($_SESSION['user_turn'])){
    $user_turn = $_SESSION['user_turn'];
} else {
    $user_turn = 0;
}
// check if it is a new game (not included in above for if the leaderboard was accessed already)
if ($user_turn == 0) {
    $user_turn = rand(1, $num_users);
    $_SESSION['user_turn'] = $user_turn;

    
}
$turn_text = 'It is '.$_SESSION['user'.$user_turn]."'s turn";

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
            <div>
                <div class="scoreboard">
                    <ul>
                        <?=$scoreboard?>
                    </ul>
                </div>
                <div class="turn"><h2><?=$turn_text?></h2></div>
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
