<?php
include 'common.php';

// function for randomizing category placements
function getCategories(){
    // array storing all category names: should be updated if more categories are added
    $all_cats = array("Movies", "Geography", "Riddles", "Sports", "Music");
    $game_cats = array();

    // get category placements
    $this_cat = $all_cats[array_rand($all_cats)];
    array_push($game_cats, $this_cat);
    // get last 4 categories in order
    for ($i = 0; $i < 4; $i++){
        $this_cat = $all_cats[array_rand($all_cats)];
        while (in_array($this_cat, $game_cats)){
            $this_cat = $all_cats[array_rand($all_cats)];
        }
        array_push($game_cats, $this_cat);
    }

    return $game_cats;
}

// function for getting and setting this game's question bank
function getQuestions($game_diff, $game_cats){
    // get array of all questions, grouped by category
    $exist_qs = array();
    $this_cat = '';
    $cat_ptr = -1;
    foreach(file('questions'.$game_diff.'.txt') as $line) {
        // assumes questions are grouped by category with spaces between
        $this_q = explode(';', trim($line));
        if ($this_q[0] === ''){
            continue;
        }
        if ($this_q[0] !== $this_cat){
            $cat_ptr++;
            $this_cat = $this_q[0];
            array_push($exist_qs, array());
        }
        array_push($exist_qs[$cat_ptr], $this_q);
    }

    // put together question bank for this game: 5 categories x 5 levels + 1 for cat names
    $game_qs = array(
        array('cat_name' => '', 1 => array('q' => '', 'a' => ''), 2 => array('q' => '', 'a' => ''), 3 => array('q' => '', 'a' => ''), 4 => array('q' => '', 'a' => ''), 5 => array('q' => '', 'a' => '')), 
        array('cat_name' => '', 1 => array('q' => '', 'a' => ''), 2 => array('q' => '', 'a' => ''), 3 => array('q' => '', 'a' => ''), 4 => array('q' => '', 'a' => ''), 5 => array('q' => '', 'a' => '')), 
        array('cat_name' => '', 1 => array('q' => '', 'a' => ''), 2 => array('q' => '', 'a' => ''), 3 => array('q' => '', 'a' => ''), 4 => array('q' => '', 'a' => ''), 5 => array('q' => '', 'a' => '')), 
        array('cat_name' => '', 1 => array('q' => '', 'a' => ''), 2 => array('q' => '', 'a' => ''), 3 => array('q' => '', 'a' => ''), 4 => array('q' => '', 'a' => ''), 5 => array('q' => '', 'a' => '')), 
        array('cat_name' => '', 1 => array('q' => '', 'a' => ''), 2 => array('q' => '', 'a' => ''), 3 => array('q' => '', 'a' => ''), 4 => array('q' => '', 'a' => ''), 5 => array('q' => '', 'a' => ''))
        );

    // set category names in game_qs and pick questions for each slot
    foreach ($game_cats as $i => $cat){
        $game_qs[$i]['cat_name'] = $cat;
        $q_bank = array();
        // find the category's question bank in exist_qs
        foreach ($exist_qs as $cat_bank){
            if ($cat_bank[0][0] === $cat){
                $q_bank = $cat_bank;
            }
        }

        // divide questions into levels and pick random among that
        for ($j = 1; $j <= 5; $j++){
            $level_qs = array();
            foreach ($q_bank as $q){
                if ($j == (int)$q[1]){
                    $level_qs[] = $q;
                }
            }
            $rand_q = $level_qs[array_rand($level_qs)];
            $game_qs[$i][$j]['q'] = $rand_q[2]; // set question text
            $game_qs[$i][$j]['a'] = $rand_q[3];
        }
        
    }

    return $game_qs;

}

// ai difficulty: move answers
function diffCheck($i, $newAns){
    $_SESSION['last_3_ans_'.$i][0] = $_SESSION['last_3_ans_'.$i][1];
    $_SESSION['last_3_ans_'.$i][1] = $_SESSION['last_3_ans_'.$i][2];
    $_SESSION['last_3_ans_'.$i][2] = $newAns;
    $num_right = 0;
    $num_notset = 0;
    for ($j = 0; $j < 3; $j++){
        if($_SESSION['last_3_ans_'.$i][$j] == 1){
            $num_right++;
        } elseif($_SESSION['last_3_ans_'.$i][$j] == 2){
            $num_notset++;
        }
    }
    if ($num_notset == 0) {
        if ($num_right > 1 && $_SESSION['ai_diff'] < 3){
            $_SESSION['ai_diff']++;
        } elseif ($num_right < 2 && $_SESSION['ai_diff'] > 1) {
            $_SESSION['ai_diff']--;
        }
    }
}

session_start();

if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
}

// ensure all users are logged in, and get/initiate points if so
for ($i = 1; $i <= $num_users; $i++) {
    if (!isset($_SESSION['user' . $i]) || $_SESSION['user' . $i] === '') {
        $_SESSION['game_error'] = "Error: not all users are signed in. Please log in all players before starting the game.";
        header("Location: login.php");
        exit();
    } else {
        if (!isset($_SESSION['user' . $i . '_points'])){
            // initiate point session var
            $_SESSION['user' . $i . '_points'] = 0;
        }
        // ai difficulty: check for last 3 answers
        if (!isset($_SESSION['last_3_ans_'.$i])){
            $_SESSION['last_3_ans_'.$i] = array(2, 2, 2); // wrong = 0, right = 1, 2 = not enough played
        }
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

// check if questions have already been generated
if (!isset($_SESSION['questions'])){
    $game_cats = getCategories();
    $qs1 = getQuestions(1, $game_cats);
    $qs2 = getQuestions(2, $game_cats);
    $qs3 = getQuestions(3, $game_cats);
    $qs = array($qs1, $qs2, $qs3);
    $_SESSION['questions'] = $qs;
} else {
    $qs = $_SESSION['questions'];
}

// check if any questions have been played already
if (!isset($_SESSION['prev_questions'])) {
    $_SESSION['prev_questions'] = array();
}

// ai difficulty: check if difficulty already set
if (!isset($_SESSION['ai_diff'])){
    $game_diff = 2; // auto set to medium
    $_SESSION['ai_diff'] = $game_diff;
} else {
    $game_diff = $_SESSION['ai_diff'];
}

// question element ids/rep in prev_questions: "q"category index-question level (eg q0-2: category 0, level 2)

// get question board + text setup
$game_cats = array();
$q_board_html = "";
$curr_q_text = "Choose a question"; // current q id, user who selected the questio
for ($i = 0; $i < 5; $i++){
    $q_board_html = $q_board_html . "<div class=\"category-column\"><div class=\"question\" id=\"c$i\">".$qs[$game_diff-1][$i]['cat_name']."</div>";
    $game_cats[] = $qs[$game_diff-1][$i]['cat_name'];
    for ($j = 1; $j < 6; $j++){
        if (isset($_POST["q$i-$j"])){
            $_SESSION['curr_question'] = array("q$i-$j", $user_turn, $game_diff);
            array_push($_SESSION['prev_questions'], "q$i-$j");
            $curr_q_text = "Question: " . $qs[$game_diff-1][$i][$j]['q'];
            $q_board_html = $q_board_html . "<form class=\"question\" method=\"post\"><button class=\"question\" type=\"submit\" id=\"q$i-$j\" name=\"q$i-$j\" value=\"q$i-$j\" disabled>". $j * 200 ."</button></form>";
        } elseif (in_array("q$i-$j", $_SESSION['prev_questions'])) {
            $q_board_html = $q_board_html . "<form class=\"question\" method=\"post\"><button class=\"question\" type=\"submit\" id=\"q$i-$j\" name=\"q$i-$j\" value=\"q$i-$j\" disabled>". $j * 200 ."</button></form>";
        } else {
            $q_board_html = $q_board_html . "<form class=\"question\" method=\"post\"><button class=\"question\" type=\"submit\" id=\"q$i-$j\" name=\"q$i-$j\" value=\"q$i-$j\">". $j * 200 ."</button></form>";
        }
    }
    $q_board_html = $q_board_html . "</div>";
}

// check answer and swap turns if needed
if (isset($_POST['answer'])){
    $curr_cat = (int) substr($_SESSION['curr_question'][0], 1, 1);
    $curr_q = (int) substr($_SESSION['curr_question'][0], 3, 1);
    $curr_diff = (int) $_SESSION['curr_question'][2];
    // right
    if (strpos(strtolower($_POST['answer']), strtolower($qs[$curr_diff-1][$curr_cat][$curr_q]['a'])) !== false) {
        if (sizeof($_SESSION['prev_questions']) == 25){
            $_SESSION['user'.$user_turn.'_points'] += 200 * $curr_q;
            header("Location: leaderboard.php");
            exit();
        } else {
            $_SESSION['curr_question'][0] = "";
            $curr_q_text = "Correct answer! Points awarded to " . $_SESSION['user'.$user_turn] . ". Choose next question";
            $_SESSION['user'.$user_turn.'_points'] += 200 * $curr_q;
        }
        diffCheck($user_turn, 1);
    // wrong
    } else {
        $next_user = (($user_turn) % $num_users) + 1;
        if ($next_user == $_SESSION['curr_question'][1]){
            // total questions answered
            if (sizeof($_SESSION['prev_questions']) == 25){
            header("Location: leaderboard.php");
            exit();
            } else {
                $curr_q_text = "No one was correct. The correct answer was \"" . $qs[$curr_diff-1][$curr_cat][$curr_q]['a'] . "\". Choose next question";
                $user_turn = rand(1, $num_users);
                $_SESSION['curr_question'][0] = "";
            }
            diffCheck($user_turn, 0);
        } else {
            $curr_q_text = "Question: " . $qs[$curr_diff-1][$curr_cat][$curr_q]['q'];
            $user_turn = $next_user;
            diffCheck($user_turn, 0);
        }
        $_SESSION['user_turn'] = $user_turn;
    }
}

$turn_text = 'It is '.$_SESSION['user'.$user_turn]."'s turn";

$ans_disabled = " required";
if ($_SESSION['curr_question'][0] == ""){
    $ans_disabled = " disabled";
}

$scoreboard = '';
for ($i = 1; $i <= $num_users; $i++){
    $scoreboard = $scoreboard . "<li>" . $_SESSION['user'.$i] . ": " . $_SESSION['user'.$i.'_points'] . " points</li>";
}

$game_diff_text = '';
if ($game_diff == 1){
    $game_diff_text = "Easy";
} elseif ($game_diff == 2){
    $game_diff_text = "Medium";
} else {
    $game_diff_text = "Hard";
}

?>

<?=$header_pre_title?><?=$header_post_title?>
            <?php
            if (isset($_SESSION['game_error'])) {
                echo "<p class='error'>" . $_SESSION['game_error'] . "</p>";
                unset($_SESSION['game_error']);
                exit();
            }
            ?>
            <div>
                <div class="scoreboard">
                    <ul>
                        <?=$scoreboard?>
                    </ul>
                </div>
                <div class="turn"><h2><?=$curr_q_text?></h2></div>
                <div class="turn"><h3><?=$turn_text?></h3></div>
                <div class="turn"><p>Dynamic Difficulty: <?=$game_diff_text?></p></div>
            </div>
            <div class="questionboard">
                <?=$q_board_html?>
            </div>
            <form class="answer-box" method="post">
                <label for="answer">Answer: </label>
                <input type="text" name="answer" id="answer" placeholder="What is a pigeon?"<?=$ans_disabled?>>
                <button type="submit">Guess</button>
            </form>
<?=$bottom?>
