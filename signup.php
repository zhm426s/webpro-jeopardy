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
<html>

<head>
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Signup</h1>

    <?php if ($error != ""): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="user1" placeholder="Username">
        <input type="password" name="pass1" placeholder="Password">
        <button type="submit">Create Account</button>
    </form>

    <a href="login.php">Already have an account? Login</a>

</body>

</html>