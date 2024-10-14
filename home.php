<?php
session_start();

$username = '';
$connection = new mysqli('localhost', 'root', '', 'todo-app');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$stmt = $connection->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
} else {
    die('Something wrong.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_destroy();
    header("Location: index.php");
    exit();
}

?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=divice-width, initial-scale=1.1">
        <title>Home</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/home.css">
    </head>
    <body>
        <main>
            <div class="app-bar">
                <?php
                    echo "<h3> Welcome, " . $username . "</h3>";
                ?>
            </div>
            <div class="content">
                <h1>Under Development ;) </h1>
                <form action="" method="POST">
                    <button type="submit">Sign Out</button>
                </form>
            </div>
        </main>
    </body>
</html>
