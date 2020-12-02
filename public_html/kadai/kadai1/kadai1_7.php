<?php

  $fp = fopen("kadai1_6_output.txt", "r");
  $arr = [];
  
  while ($line = fgets($fp)) {
    array_push($arr, $line);
  }

  fclose($fp);

  foreach($arr as $el) {
    echo $el."<br/>";
  }

?>