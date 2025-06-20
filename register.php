<?php  
    include('header.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="process.php" method="post">
        <input type="hidden" name="process_function" value="register">
        <label>username:</label>
        <input type="text" name="username"> <br>
        <label>password:</label>
        <input type="password" name="password"> <br>
        <label>Admin:</label>
        <input type="checkbox" name="user_priv" value="admin"> <br>
        <input type="submit" value="Register">
    </form>

    <?php 
    session_start();
        if(isset($_SESSION['user_created'])){
            if($_SESSION['user_created'] == true){
                echo"User has been created";
            }
            elseif($_SESSION['user_created'] == false){
                echo"User has NOT been created";
            }
        }
        unset($_SESSION['user_created']);
    ?>
</body>
</html>
<?php
    include('footer.html');
?>
