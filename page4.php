<html>
 <head>
 <title>Dynamic CSS</title>
 <?php
 include("menu.php");
 ?>
 
 <?php
 if (isset($_POST["style"])) {
 $thisStyle=$_POST["style"];
 } else {
 $thisStyle="0";
 }
 print "<link rel=\"stylesheet\" href=\"style".$thisStyle.".css\">";
 ?>
 </head>
 <body>
 <h1>What color is this heading?</h1>
 
 <!-- Buttons -->
 <form method="post" action="<?= $_SERVER["PHP_SELF"]; ?>">
 <input type="submit" name="buttonStyle" value="0"><BR>
 <input type="submit" name="buttonStyle" value="1"><BR>
 <input type="submit" name="buttonStyle" value="2">
 </form>
 
<!-- Radio Buttons and Drop Down menus. -->
 <form method="post" action="<?= $_SERVER["PHP_SELF"]; ?>">
        <input type="radio" name="radioStyle" value="0" id="style0">
        <label for="style0">Style 0</label><br>
        <input type="radio" name="radioStyle" value="1" id="style1">
        <label for="style1">Style 1</label><br>
        <input type="radio" name="radioStyle" value="2" id="style2">
        <label for="style2">Style 2</label><br>

        <label for="styleSelect">Select a style:</label>
        <select name="selectStyle" id="styleSelect">
            <option value="0">Style 0</option>
            <option value="1">Style 1</option>
            <option value="2">Style 2</option>
        </select>

        <input type="submit" value="Apply Style" name="submitButton">
    </form>
 </body>
</html>
