<?php
session_start();
if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
}

for ($i = 1; $i <= $num_users; $i++) {
    if ((!isset($_SESSION['user' . $i]) || $_SESSION['user' . $i] === '') && !isset($_SESSION['user' . $i . '_points'])) {
        $_SESSION['leaderboard_error'] = "Error: not all users are signed in. Please complete all logins before viewing results.";
        header("Location: login.php");
        exit();
    }
}

$leaderboard = array();

for ($i = 1; $i <= $num_users; $i++) {
    if (isset($_SESSION['user' . $i])) {
        $username = $_SESSION['user' . $i];
        $points = isset($_SESSION['user' . $i . '_points']) ? $_SESSION['user' . $i . '_points'] : 0;

        $leaderboard[] = array(
            'username' => $username,
            'points' => $points
        );
    }
}

// sort highest to lowest points
usort($leaderboard, function ($a, $b) {
    return $b['points'] - $a['points'];
});
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
        <h2>Game Results</h2>
        <ol class="leaderboard">
            <?php
            foreach ($leaderboard as $player) {
                echo "<li>" . htmlspecialchars($player['username']) . " - " . $player['points'] . " Points</li>";
            }
            ?>//display leaderboard
        </ol>
        <a href="login.php"><button class="link-button" type="button">Go to Home</button></a>
        <a href="play.php"><button class="link-button" type="button">Play Again!</button></a>
    </main>
</body>

</html>