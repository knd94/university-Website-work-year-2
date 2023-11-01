<html>
 <head>
 <title>Dynamic CSS</title>
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
 <h1>What colour is this heading?</h1>
 <form method="post" action="<?= $_SERVER["PHP_SELF"]; ?>">
 <input type="submit" name="style" value="0"><BR>
 <input type="submit" name="style" value="1"><BR>
 <input type="submit" name="style" value="2">
 </form>
 </body>
 </html>
 