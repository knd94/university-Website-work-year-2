<?php
   $filename = "person.data";
   $fp = @fopen($filename, "rb") or die("Couldn't open file"); 
   $data = fread($fp, filesize($filename)); 
   while(!feof($fp)) { 
     $data .= fgets($fp, 1024); 
   } 
   fclose($fp); 

   $values = explode("\n", $data); 
   // NOTE: if you create your data file on Windows you need to replace the line above with the one below
   // $values = explode("\r\n", $data); 

   echo "Name: " . $values[0] . " " . $values[1] . "<br>"; 
   echo "Age: " . $values[2] . "<br>"; 
   echo "Sex: " . $values[3]; 
?>