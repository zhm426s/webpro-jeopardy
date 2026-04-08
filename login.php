<?php
session_start();
// get existing user data from the file
$exist_users = array();
foreach(file('users.txt') as $line) {
    $this_user = explode(';', $line);
    array_push($exist_users, $this_user);
}

// check how many users there are
if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
    }

// check if add user button was pressed
if (isset($_POST['addUser']) && htmlspecialchars($_POST['addUser']) === 'add') {
    $num_users++;
} elseif (htmlspecialchars($_POST['addUser']) === 'sub') {
    if ($num_users > 2){
        $num_users--;
    }
}
if ($num_users == 2){
    $remove_disable = 'disabled>Minimum users';
} else {
    $remove_disable = '>Remove a User';
}

// set num users
$_SESSION['num_users'] = $num_users;

// check if a new user signed up via signup.php
if ((isset($_POST['user']) && htmlspecialchars($_POST['user']) !== '') && (isset($_POST['pass']) && htmlspecialchars($_POST['pass']) !== '')){
    $new_user = htmlspecialchars($_POST['user']);
    $new_pass = htmlspecialchars($_POST['pass']);
    $new_hash_pass = password_hash($pass, PASSWORD_DEFAULT); // hash password for security

    // TODO validate that the new username is not taken, redirect back to signup.php if it is

    $already_signed_in = false;
    // check that user is not already signed in
    for ($i = 1; $i <= $num_users; $i++){
        if ($_SESSION['user'.$i] === $new_user){
            $already_signed_in = true;
        }
    }

    if(!$already_signed_in){
        // add new user to users.txt
        file_put_contents('users.txt', implode(';', array($new_user, $new_hash_pass))."\n", FILE_APPEND);

        // check for next available user session
        for ($i = 1; $i <= $num_users; $i++){
            if (!isset($_SESSION['user'.$i])||htmlspecialchars($_SESSION['user'.$i]) === ''){
                $_SESSION['user'.$i] = $new_user; // set user session
                $_POST['user'] = '';
                $_POST['pass'] = '';
                break;
            }
        }
    }


}

// TODO implement logout

$loggedin = array(); // array will store numbers of users that are logged in

$login_forms = array(); // array will store the form visual for all users, logged in or not

// check which users have posted a login
for ($i = 1; $i <= $num_users; $i++){
    // first check if user is already logged in, skip if so
    if (isset($_SESSION['user'.$i]) && $_SESSION['user'.$i] !== '') {
        $this_user = $_SESSION['user'.$i];

        array_push($login_forms, "<form class=\"login-form\" method=\"post\">
                <legend>User $i</legend>
                <label for=\"user$i\">Username: </label>
                <input type=\"text\" name=\"user$i\" id=\"user$i\" placeholder=\"$this_user\" disabled>
                <label for=\"pass$i\">Password: </label>
                <input type=\"password\" name=\"pass$i\" id=\"pass$i\" disabled>
                <button type=\"submit\">Log out</button>
            </form>");
     
    // check if user info was posted
    } elseif (isset($_POST['user'.$i]) && isset($_POST['pass'.$i])){
        array_push($login_form_log, "User $i block posted");
        $this_user = htmlspecialchars($_POST['user'.$i]);
        $this_pass = htmlspecialchars($_POST['pass'.$i]);

        // search for username in users.txt
        $found_user = '';
        foreach ($exist_users as $user){
            if($user[0] === $this_user){
                $found_user = $user;
                break;
            }
        }
        // TODO give error if user was not found

        // check password
        if (password_verify($this_pass, $found_user[1])){
            $_SESSION['user'.$i] = $this_user;
            array_push($login_forms, "<form class=\"login-form\" method=\"post\">
                <legend>User $i</legend>
                <label for=\"user$i\">Username: </label>
                <input type=\"text\" name=\"user$i\" id=\"user$i\" placeholder=\"$this_user\" disabled>
                <label for=\"pass$i\">Password: </label>
                <input type=\"password\" name=\"pass$i\" id=\"pass$i\" placeholder=\"\" disabled>
                <button type=\"submit\">Log out</button>
            </form>");
        } else {
            // TODO give error if password does not match
            array_push($login_forms, "<form class=\"login-form\" method=\"post\">
                <legend>User $i</legend>
                <label for=\"user$i\">Username: </label>
                <input type=\"text\" name=\"user$i\" id=\"user$i\" placeholder=\"epictriviafan_123\" required>
                <label for=\"pass$i\">Password: </label>
                <input type=\"password\" name=\"pass$i\" id=\"pass$i\" required>
                <button type=\"submit\">Log In</button>
            </form>");
        }
    } else {
        array_push($login_forms, "<form class=\"login-form\" method=\"post\">
                <legend>User $i</legend>
                <label for=\"user$i\">Username: </label>
                <input type=\"text\" name=\"user$i\" id=\"user$i\" placeholder=\"epictriviafan_123\" required>
                <label for=\"pass$i\">Password: </label>
                <input type=\"password\" name=\"pass$i\" id=\"pass$i\" required>
                <button type=\"submit\">Log In</button>
            </form>");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Jeopardy- Login</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Jeopardy</h1>
        </header>
        <main>
            <h2>Login</h2>
            <?php
            for ($i = 0; $i < $num_users; $i++){
                if ($i % 2 == 0){
                    echo '<div class="form-group">';
                    echo $login_forms[$i];
                    if ($i == $num_users - 1) {
                        echo '</div>';
                    }
                } elseif ($i % 2 == 1) {
                    echo $login_forms[$i];
                    echo '</div>';
                }

            }
            ?>
            <form method="post">
                <button class="link-button" type="submit" value="add" name="addUser" id="addUser">Add Another User</button>
            </form>
            <form method="post">
                <button class="link-button" type="submit" value="sub" name="addUser" id="subUser"<?=$remove_disable?></button>
            </form>
            <a href="signup.php"><button class="link-button" type="button">Create New Account</button></a>
            <a href="play.php"><button class="link-button" type="button">Play Jeopardy!</button></a>
        </main>
    </body>
</html>

