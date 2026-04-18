<?php

function getFormHTML($i, $in){
    if ($in === "in"){
        return "<form class=\"login-form\" method=\"post\">
                    <legend>User $i</legend>
                    <label for=\"user$i\">Username: </label>
                    <input type=\"text\" name=\"user$i\" id=\"user$i\" placeholder=\"".$_SESSION['user'.$i]."\" disabled>
                    <label for=\"pass$i\">Password: </label>
                    <input type=\"password\" name=\"pass$i\" id=\"pass$i\" disabled>
                    <form method=\"post\">
                    <button class=\"link-button\" type=\"submit\" value=\"out$i\" name=\"logout\" id=\"logout\">Log Out</button>
                    </form>
                    </form>";
    } else {
        return "<form class=\"login-form\" method=\"post\">
            <legend>User $i</legend>
            <label for=\"user$i\">Username: </label>
            <input type=\"text\" name=\"user$i\" id=\"user$i\" placeholder=\"epictriviafan_123\" required>
            <label for=\"pass$i\">Password: </label>
            <input type=\"password\" name=\"pass$i\" id=\"pass$i\" required>
            <button type=\"submit\">Log In</button>
            </form>";
    }
}



session_start();
// get existing user data from the file
$exist_users = array();
foreach (file('users.txt') as $line) {
    $this_user = explode(';', trim($line));
    array_push($exist_users, $this_user);
}

// check how many users there are
if (!isset($_SESSION['num_users']) || $_SESSION['num_users'] < 2) {
    $num_users = 2;
} else {
    $num_users = $_SESSION['num_users'];
}

// check if add user button was pressed
if (isset($_POST['addUser']) && $_POST['addUser'] === 'add') {
    $num_users++;
} elseif ($_POST['addUser'] === 'sub') {
    if ($num_users > 2) {
        $num_users--;
    }
}
if ($num_users == 2) {
    $remove_disable = 'disabled>Minimum users';
} else {
    $remove_disable = '>Remove a User';
}

// set num users
$_SESSION['num_users'] = $num_users;

// check if a new user signed up via signup.php
if ((isset($_POST['user']) && $_POST['user'] !== '') && (isset($_POST['pass']) && $_POST['pass'] !== '')) {
    $new_user = htmlspecialchars($_POST['user']);
    $new_pass = $_POST['pass'];
    $new_hash_pass = password_hash($new_pass, PASSWORD_DEFAULT); // hash password for security

    $username_taken = false;

    // check against users already stored in file
    foreach ($exist_users as $user) {
        if (isset($user[0]) && $user[0] === $new_user) {
            $username_taken = true;
            break;
        }
    }

    if ($username_taken) {
        // store error in session so signup.php can display it
        $_SESSION['signup_error'] = "Username already taken.";

        header("Location: signup.php");
        exit();
    }

    $already_signed_in = false;
    // check that user is not already signed in
    for ($i = 1; $i <= $num_users; $i++) {
        if ($_SESSION['user' . $i] === $new_user) {
            $already_signed_in = true;
        }
    }

    if (!$already_signed_in) {
        // add new user to users.txt
        file_put_contents('users.txt', implode(';', array($new_user, $new_hash_pass)) . "\n", FILE_APPEND);

        // check for next available user session
        for ($i = 1; $i <= $num_users; $i++) {
            if (!isset($_SESSION['user' . $i]) || $_SESSION['user' . $i] === '') {
                $_SESSION['user' . $i] = $new_user; // set user session
                $_POST['user'] = '';
                $_POST['pass'] = '';
                break;
            }
        }
    }
}

$loggedin = array(); // array will store numbers of users that are logged in

$login_forms = array(); // array will store the form visual for all users, logged in or not

// check which users have posted a login
for ($i = 1; $i <= $num_users; $i++) {
    // first check if user is already logged in, skip if so
    if (isset($_SESSION['user' . $i]) && $_SESSION['user' . $i] !== '') {
        if (isset($_POST['logout']) && $_POST['logout'] === "out$i") {
            // unset the user's session var
            unset($_SESSION['user' . $i]);
            $_SESSION['logout_message'] = "User logged out successfully.";
            array_push($login_forms, getFormHTML($i, "out"));
        } else {
            $this_user = $_SESSION['user' . $i];

            array_push($login_forms, getFormHTML($i, "in"));
        }

        // check if user info was posted
    } elseif (isset($_POST['user' . $i]) && isset($_POST['pass' . $i])) {
        $this_user = trim($_POST['user' . $i]);

        // search for username in users.txt
        $found_user = array('', '');
        foreach ($exist_users as $user) {
            if ($user[0] === $this_user) {
                $found_user = $user;
                break;
            }
        }
        if ($found_user[0] === '') {
            $errors[$i] = "User not found.";
        }
        // check password
        if (password_verify($_POST['pass' . $i], addslashes($found_user[1]))) {
            $_SESSION['user' . $i] = $this_user;
            array_push($login_forms, getFormHTML($i, "in"));
        } else {
            $errors[$i] = "Incorrect password.";
            array_push($login_forms, getFormHTML($i, "out"));
        }
    } else {
        array_push($login_forms, getFormHTML($i, "out"));
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
        for ($i = 0; $i < $num_users; $i++) {
            if ($i % 2 == 0) {
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
        <?php
        if (isset($_SESSION['game_error'])) {
            echo "<p class='error'>" . $_SESSION['game_error'] . "</p>";
            unset($_SESSION['game_error']);
        }
        ?>
        <?php
        if (isset($_SESSION['logout_message'])) {
            echo "<p class='success'>" . $_SESSION['logout_message'] . "</p>";
            unset($_SESSION['logout_message']);
        }
        ?>
        <form method="post">
            <button class="link-button" type="submit" value="add" name="addUser" id="addUser">Add Another User</button>
        </form>
        <form method="post">
            <button class="link-button" type="submit" value="sub" name="addUser" id="subUser" <?= $remove_disable ?></button>
        </form>
        <a href="signup.php"><button class="link-button" type="button">Create New Account</button></a>
        <a href="play.php"><button class="link-button" type="button">Play Jeopardy!</button></a>
    </main>
</body>

</html>