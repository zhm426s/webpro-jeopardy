<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jeopardy- Signup</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>

<script>
    function validateForm() {
        let username = document.forms["signupForm"]["username"].value;
        let password = document.forms["signupForm"]["password"].value;

        if (username === "" || password === "") {
            alert("All fields must be filled out");
            return false;
        }

        if (password.length < 6) {
            alert("Password must be at least 6 characters");
            return false;
        }

        return true;
    }
</script>

<body>
    <header>
        <h1>Jeopardy</h1>
    </header>
    <main>
        <h2>Sign Up</h2>
        <form class="signup-form" method="post" action="login.php" name="signupForm" onsubmit="return validateForm()">
            <legend>New User</legend>
            <label for="user">Username: </label>
            <input type="text" name="user" id="user" placeholder="epictriviafan_123" required>
            <label for="pass">Password: </label>
            <input type="password" name="pass" id="pass" required>
            <button type="submit">Create Account</button>
        </form>
        <a href="login.php"><button class="link-button" type="button">Log in with existing account</button></a>
    </main>
</body>

</html>