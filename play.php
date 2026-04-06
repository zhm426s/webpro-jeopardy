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
                        <li>User 1: [username], [points] Points</li>
                    </ul>
                </div>
                <div class="turn"><h2>It is [username]'s turn</h2></div>
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