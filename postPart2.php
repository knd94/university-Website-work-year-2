<html>
   <head>
     <title>$_POST - part 2</title>
   </head>
   <body>
     <?php
       if (isset($_POST["frmModuleCode"]) && $_POST["frmModuleCode"] != "") {
         echo "<p>I love ".$_POST["frmModuleCode"]."</p>";
       } else {
         echo "<p>You haven't entered a Module Code - go back and try again</p>";
       }
     ?>
   </body>
 </html>