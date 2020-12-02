<?php

  $fp = fopen("kadai1_2_output.txt", "r");
  
  while ($line = fgets($fp)) {
    echo "$line<br/>";
  }

  fclose($fp);

?>