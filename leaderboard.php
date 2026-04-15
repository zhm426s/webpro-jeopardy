<?php
session_start();
if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
    }

for ($i = 1; $i <= $num_users; $i++){
    if ((!isset($_SESSION['user'.$i]) || $_SESSION['user'.$i] === '') && !isset($_SESSION['user'.$i.'_points'])){
        // TODO validation: display error message saying that not all users are signed in
        echo "Error: not all users signed in"; // for testing only
        exit;
    }
}

// TODO leaderboard logic: get and sort point values
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Jeopardy- Leaderboard</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Jeopardy</h1>
        </header>
        <main>
            <h2>Game Over!</h2>
            <ol class="leaderboard">
                <li>[Username] [Points]</li>
            </ol>
            <a href="login.php"><button class="link-button" type="button">Go to Home</button></a>
            <a href="play.php"><button class="link-button" type="button">Play Again!</button></a>
        </main>
    </body>
</html>
