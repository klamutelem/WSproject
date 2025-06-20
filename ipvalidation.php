<?php
    include("header.php");
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <?php
    /*
    This part below contains an alert display mechanism. Whenever we pass an IP to a process.php we can call for a function that will check if the IP is valid
    If the IP is not valid it will return a session variable called $_SESSION['result'] which contains a string that will be displayed in the alert box
    For displaying the alert box we are using javascript script that gets echoed in the PHP script
    */
        if(isset($_SESSION['result'])){
            echo '<script>alert("'.$_SESSION['result'].'")</script>';
            unset($_SESSION['result']);
            //we then remove $_SESSION['result'] so it will dissapear after refreshing the page or clicking different form button
        } 
    ?>
    <button class="hideDivBtn">Public/private</button>
    <div class="standardDiv" style="display: block">
        <form action="process.php" method="post">
            <h4>Check if your IP is public od private</h4>
            <input type="hidden" name="process_function" value="check_if_private">
            <label>What is your IP</label> 
            <input type="text" placeholder="xxx.xxx.xxx.xxx" required pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" name="IP_string" />
            <input type="submit" class="buttons" value="Check"><br>      
        </form>
    </div>
    <?php
        if(isset($_SESSION['is_private'])){
            echo '<script>alert("'.$_SESSION['is_private'].'")</script>';
            unset($_SESSION['is_private']);
        }
    ?>
    <!--
    Below we create a new from that accepts only IPv4 format and calls for the binary_converter function in process.php
    -->
    <button class="hideDivBtn">dropDownBtn</button>
    <div class="standardDiv">
        <form action="process.php" method="post">
            <h4>Binary converter</h4>
            <input type="hidden" name="process_function" value="binary_converter">
            <label>What is your IP</label> 
            <input type="text" placeholder="xxx.xxx.xxx.xxx" required pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" name="IP_string" />
            <input type="submit" class="buttons" value="Check"><br>
        </form>
    </div>
    <?php
        if(isset($_SESSION['binary_array'])){
            foreach($_SESSION['binary_array'] as $x){
                echo"$x";
            }
            unset($_SESSION['binary_array']);
        }    
    ?>
    <button class="hideDivBtn">dropDownBtn</button>
    <div class="standardDiv">
        <form action="process.php" method="post">
            <h4>Convert to a /xx format</h4>
            <input type="hidden" name="process_function" value="slash_notation_converter">
            <label>What is your mask</label> 
            <input type="text" placeholder="xxx.xxx.xxx.xxx" required pattern="\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}" name="IP_string" />
            <input type="submit" class="buttons" value="Check"><br>
        </form>
    </div>
    <?php
        if(isset($_SESSION['/mask'])){
            echo "/" . $_SESSION['/mask'];
            unset($_SESSION['/mask']);
        }    
    ?>
    <button class="hideDivBtn">dropDownBtn</button>
    <div class="standardDiv">
        <form action="process.php" method="post">
            <h4>Convert to a full subnet mask format</h4>
            <input type="hidden" name="process_function" value="subnet_mask_converter">
            <label>What is your /xx mask</label> 
            <input type="text" placeholder="xx" name="slash_subnet_mask" />
            <input type="submit" class="buttons" value="Check"><br>
        </form>
    </div>
    <?php
        if(isset($_SESSION['dotted_decimal_mask'])){
            echo '<script>alert("'.$_SESSION['dotted_decimal_mask'].'")</script>';
            unset($_SESSION['dotted_decimal_mask']);
        } 
    ?>
    <script src="process.js"></script>
</body>
</html>

<?php
    include("footer.html");
?>
