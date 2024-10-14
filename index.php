<?php

$connection = new mysqli('localhost', 'root', '', 'todo-app');
$error_message = '';

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) == 0) {
        $error_message = 'Username are empty.';
    } else if (strlen($password) == 0) {
        $error_message = 'Password are empty.';
    }

    if (empty($error_message)) {
        $stmt = $connection->prepare("SELECT password, user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password'])) {
                session_start();

                $_SESSION['user_id'] = $row['user_id'];

                header('Location: home.php');
                exit();
            } else {
                $error_message = 'Incorrent password.';
            }
        } else {
            $error_message = 'Incorrect username.';
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=divice-width, initial-scale=1.1">
        <title>Todo App</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/sign_in.css">
    </head>
    <body>
        <main>
            <div class="image"></div>
            <form method="POST" action="">
                <h1>Welcome to Library</h1>
                <h5>Let get started!</h5>

                <input type="text" placeholder="Username" name="username"/>
                <input type="password" placeholder="Password" name="password"/>

                <button type="submit">Sign In</button>
                <?php
                    if ($error_message !== '') {
                        echo '<div style="margin-top: 5px;color: red;">' . $error_message . '</div>';
                    }
                ?>

                <p>Din't have account? <a href="sign_up.php">Sign Up</a></p>
            </form>
        </main>
    </body>
</html>
