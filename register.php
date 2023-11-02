<?php session_start(); ?>
 <html>
   <head>
     <title>Sessions - Registration Page</title>
   </head>
   <body>
     <form method="post" action="<?= $_SERVER["PHP_SELF"]; ?>">
 <?php
   $firstName = ""; $lastName = "";

   if (isset($_POST["updateDetails"])) { // if a request to update the session has been received...
     $_SESSION["firstName"] = $_POST["firstName"];
     $_SESSION["lastName"] = $_POST["lastName"];
     echo "<h1>UPDATED!</h1>";
   }

   if (isset($_SESSION["firstName"])) { // if the names are already set in the session...
     $firstName = $_SESSION["firstName"];
     $lastName = $_SESSION["lastName"];
   }
 ?>
     <p>Enter First Name: <input type="text" name="firstName" value="<?= $firstName; ?>"></p>
     <p>Enter Last Name: <input type="text" name="lastName" value="<?= $lastName; ?>"></p>
     <p><input type="submit" name="updateDetails" value="Update"></p>
     </form>
     <p><a href="page6.php">Back to page 1</a></p>
   </body>
 </html>