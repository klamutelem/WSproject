
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>THIS IS A LOGIN PAGE</h2>
    <form action="process.php" method="post">
        <input type="hidden" name="process_function" value="login">
        <label> Username: </label>
        <input type="text" name="username"> <br>
        <label> password: </label>
        <input type="password" name="password"> <br>
        <input type="submit" value="login">
    </form>
    <?php
    session_start();
    if(isset($_SESSION['login']) && $_SESSION['login'] == false){
        echo '<script>alert("Incorrect username or password")</script>';
    }
    elseif(isset($_SESSION['DB_check']) && $_SESSION['DB_check'] == false){
        echo '<script>alert("DB connection failed")</script>';
    }
    unset($_SESSION['login']);
    ?>
</body>
</html>