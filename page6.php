<?php session_start(); ?>
 <html>
   <head>
     <title>Sessions - Main Page</title>
   </head>
   <body>
 <?php
   if (isset($_SESSION["firstName"])) {
     echo "<h1>Welcome ".$_SESSION["firstName"]." ".$_SESSION["lastName"]."</h1>";
   } else {
     echo "<h1>Welcome Visitor - please sign in</h1>";
   }
 ?>
 <p>If you have not visited the <a href="register.php">Registration Page</a>, please do so now.</p>
 </body>
 </html>