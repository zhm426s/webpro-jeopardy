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
            <form class="login-form" method="post">
                <legend>User 1</legend>
                <label for="user1">Username: </label>
                <input type="text" name="user1" id="user1" placeholder="epictriviafan_123" required>
                <label for="pass1">Password: </label>
                <input type="password" name="pass1" id="pass1" required>
                <button type="submit">Log In</button>
            </form>
            <form class="login-form" method="post">
                <legend>User 2</legend>
                <label for="user2">Username: </label>
                <input type="text" name="user2" id="user2" placeholder="epictriviafan_456" required>
                <label for="pass2">Password: </label>
                <input type="password" name="pass2" id="pass2" required>
                <button type="submit">Log In</button>
            </form>
            <button class="link-button" type="submit" formmethod="post" value="add" name="addUser" id="addUser">Add Another User</button>
            <a href="signup.php"><button class="link-button" type="button">Create New Account</button></a>
            <a href="play.php"><button class="link-button" type="button">Play Jeopardy!</button></a>
        </main>
    </body>
</html>

