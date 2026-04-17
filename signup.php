<?php
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["user1"]);
    $password = trim($_POST["pass1"]);

    if ($username == "" || $password == "") {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $users = file("users.txt", FILE_IGNORE_NEW_LINES);
        foreach ($users as $user) {
            list($storedUser, $storedPass) = explode(";", $user); // updated line
            if ($storedUser === $username) {
                $error = "Username already exists.";
                break;
            }
        }

        if ($error == "") {
            $hashed = password_hash($password, PASSWORD_DEFAULT); // better security
            file_put_contents("users.txt", "$username;$hashed\n", FILE_APPEND); // fix
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Jeopardy- Signup</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <h1>Jeopardy</h1>
        </header>
        <main>
            <h2>Sign Up</h2>
            
            <?php if ($error != ""): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <form class="signup-form" method="post" action="login.php">
                <legend>New User</legend>
                <label for="user">Username: </label>
                <input type="text" name="user" id="user" placeholder="epictriviafan_123" required>
                <label for="pass">Password: </label>
                <input type="password" name="pass" id="pass" required>
                <button type="submit">Create Account</button>
            </form>
            <a href="login.php"><button class="link-button" type="button">Already have an account? Login</button></a>
        </main>
    </body>
</html>
