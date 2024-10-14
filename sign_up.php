<?php

$connection = new mysqli('localhost', 'root', '', 'todo-app');
$error_message = '';

if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) < 4) {
        $error_message = 'Username is too short.';
    } else if (strlen($password) < 4) {
        $error_message = 'Password is too short.';
    }

    if ($error_message === '') {
        $checkQuery = $connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $checkQuery->bind_param("s", $username);
        $checkQuery->execute();
        $checkQuery->bind_result($count);
        $checkQuery->fetch();
        $checkQuery->close();

        if ($count > 0) {
            $error_message = 'Username already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $connection->prepare("INSERT INTO users (username, password, user_id) VALUES (?, ?, UUID())");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                header('Location: index.php');
                exit();
            } else {
                $error_message = "Something went wrong: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.1">
        <title>Sign Up</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/sign_up.css">
    </head>
    <body>
    <main>
        <div class="image"></div>
        <form method="POST" action="">
            <h1>Creating Account</h1>
            <h5>Let’s get started!</h5>

            <input type="text" placeholder="Username" name="username" required/>
            <input type="password" placeholder="Password" name="password" required/>

            <button type="submit">Sign Up</button>
            <?php
                if ($error_message !== '') {
                    echo '<div style="margin-top: 5px; color: red;">' . $error_message . '</div>';
                }
            ?>

            <p>Already have an account? <a href="index.php">Sign In</a></p>
        </form>
    </main>
    </body>
</html>

