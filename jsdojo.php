
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JS DOJO</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
<?php
    include("header.php");
?>
<button id="counterBtn" class="buttons">Counter</button>
    <div id="counterContainer" style="display: none">
        <label id="countLabel">0</label><br>
        <h1>Header</h1>
        <button id="decreaseBtn" style="padding: 20px 30px;" class="buttons">decrease</button>
        <button id="resetBtn" style="padding: 20px 30px;" class="buttons">reset</button>
        <button id="increaseBtn" style="padding: 20px 30px;" class="buttons">increase</button>
    </div>
    <br>
    <div id="divBtn" class="divBtn">
        Div button
        <div id="hiddenDiv" style="display: none">
            <label id="label">This is normaly not visible</label>
            <button id="changeBtn" style="padding: 20px 30px;" class="buttons">changeLabel</button>
        </div>
    </div>
    <script src="jsdojo.js"></script>
</body>
</html>

<?php
    include("footer.html");
?>