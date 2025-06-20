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
        <input type="hidden" name="process_function" value="LB_config_parse">
        <label>Paste config here</label>
        <textarea id="LBconfig" name="LBconfig" oninput="autoResize(this)" cols="70" style="overflow:hidden;"></textarea><br>
        <label>File name</label>
        <input type="text" name="filename"> <br>
        <input type="submit" value="Parse into JSON">
    </form>

    <script>
    function autoResize(textarea) {
        textarea.style.height = 'auto'; // Reset height
        textarea.style.height = textarea.scrollHeight + 'px'; // Set height to scrollHeight
    }
    </script>

</body>
</html>
<?php
    include('footer.html');
?>