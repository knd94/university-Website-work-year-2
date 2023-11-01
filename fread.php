<?php
   $filename = "test.txt";
   $fp = fopen($filename, "r");
   $data = fread($fp, filesize($filename));
   echo $data;
?>