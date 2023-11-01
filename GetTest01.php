 <html>
   <head><title>Get Example</title></head>
   <body>
    <?php
      if (isset($_GET["name"])) {
        echo "<p>Hello ".$_GET["name"]."</p>";
      } else {
        echo "<p>I don't know who you are!</p>";
      }
   ?>
  </body>
 </html>