<header>
    <h2>Cportal killer</h2>
        <?php
        session_start();
        if($_SESSION['user_priv'] == "admin")
        echo '<a href="register.php">Register</a>';
        echo " ".'<a href="phpinfo.php">PHP INFO</a>';
        /* we want the admin href to be visible only if the user_priv session variable is admin
        That way we know that the user connected to the portal has correct rights to manage users
        */
        ?>
        <a href="ipvalidation.php">IP validation</a>
        <a href="index.php">Stuff</a>
        <a href="downloads.php">Downloads</a>
        <a href="jsdojo.php">JS Dojo</a>
        <a href="LBautomation.php">LB automation</a>
        <hr>
</header>
<?php
    if($_COOKIE['logged_in'] != 1) {
        header("Location: login.php");
    }
?>