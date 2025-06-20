<?php
    include("header.php");
    $downloads_dir = '/opt/lampp/downloads';
    $files = scandir($downloads_dir);
    //scandir will take all files in said directory and store them in array?
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h4>Downloads <br></h4>
    <?php
    foreach($files as $file){
        if($file != '.' && $file != '..' ){
            echo '<form action="process.php" method="post">
                    <label>'. htmlspecialchars($file) . '</label> 
                    <input type="hidden" name="process_function" value="download">
                    <input type="hidden" name="file" value="'. htmlspecialchars($file) . '">
                    <input type="submit" value="Download">
                    </form>';
        }
    }
    ?>
</body>
</html>
<?php
    include("footer.html");
?>