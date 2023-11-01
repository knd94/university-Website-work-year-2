<?php 
   $fp = fopen("newfile.file", "w") or die("Couldn't create file"); 
   $numBytes = fwrite($fp, "Hello, this is some text!"); 
   fclose($fp); 

   echo "Wrote $numBytes bytes to newfile.file!"; 
?>