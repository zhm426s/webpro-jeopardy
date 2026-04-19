# Jeopardy
Jeopardy is a game show-turned-web game where players take turns guessing the question to a trivia answer for points. 

## Description

### Features
- Login and signup, using sessions to keep track of logged in users. Stores a username and an encyrpted password in an external file
- An expandable question bank featuring real Jeopardy questions from the TV show, organized into categories, point levels, and dynamic difficulty levels
- A simply-styled user interface
- A random question bank generation algorithm, which ensures that, assuming there are multiple questions in the bank which match each category, point level, and difficulty level, will create a different set of questions each game
- Game logic for taking turns between users, verifying users, and checking recent answers to change the dynamic difficulty
- A leaderboard at the end of each game, which lets the players see their scores in a list sorted from most points to least points

### Technical Aspects
Jeopardy exclusively uses PHP to handle sessions and form requests to make most of its interactive features work. Features which require external storage are implemented using external text files for simplicity. 

The visuals of the website are coded entirely in HTML5 and CSS, and are dynamically loaded based on a combination of session variables and form requests.

## Usage

The basic playthrough of a game is as follows: 
- Enter via the login page (login.php), log in each player at a time if they have an existing account (or navigate to signup.php if they do not). Add more players using the "Add another player" button. 
- Once all players have been decided and logged in, click "Play Jeopardy" to enter the game. The game will randomly pick a starting player, and they will choose the first question from any on the board. 
- Each round, the player who picked the question will have the first opportunity to answer, and they will keep that power until they get aa question wrong. When they do get a question wrong, the power will go to the next player down the list. If a question passes with no correct answers, a random player will again be chosen. 
- This continues until all questions have been played, at which point it will redirect to the leaderboard page. The leaderboard will identify the winner, and list the players in order from most points to least points.

## Installation and Setup

The first thing you will need to run this project is a server capable of running PHP code. For example, WAMP/MAMP/LAMP servers are readily available for local use. 

When you have your server, download and install all files (except this readme) and place them somewhere under the public_html folder (or an equivalent location where HTML code can go. Refer to guidelines for your server).  

Access the URL for the login page. For example, it may be localhost://login.php. Refer to guidelines for your server. You should then be on the login page. 

users.txt is intentionally left blank, since it is easy to create an account via the application. If users must be added to the file externally, follow this format for each line: 

```
username;password encrypted using password_hash($password, PASSWORD_DEFAULT)
```

If you would like to add questions to the bank, ensure that they follow this format: 

```
category;point level (1-5);question text;answer text;difficulty level (1-3)
```

If adding to an existing category, ensure that the new question is placed in the file next to another question from the same category. 

If adding a new category, ensure that there are least 15 questions for it in the bank: one for each point level (1-5) times each difficulty level (1-3). Refer to the existing question bank as a template. Questions for different difficulty levels also are in different question files: difficulty 1 questions are in questions1.txt, difficulty 2 questions are in questions2.txt, etc. 

## Team

This entire project was programmed by Github users @zhm426s and @azgai. 


## Sources

Jeopardy questions are all sourced from [J-Archive.com](https://j-archive.com/index.php), a website which archives all official Jeopardy TV show questions and answers, with the exception of the Riddles category, which are sourced from [Parade.com](https://parade.com/947956/parade/riddles/).

### AI Disclosure

Generative AI was used for coding suggestions, explanations, and debugging assistance for this project.