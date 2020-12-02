<?php

  $text = "Hello World";
  $fp = fopen("kadai1_2_output.txt", "w");
  fwrite($fp, $text);
  fclose($fp);

?>