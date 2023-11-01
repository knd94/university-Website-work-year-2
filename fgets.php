<?php 
   $fp = fopen("test.txt", "r"); 
   $data = ""; 
   while(!feof($fp)) { 
     $data .= fgets($fp, 4096); 
   } 
   echo $data; 
?> 