<?php

function now() {
  return date("Y-m-d H:i:s");
}

function random($length)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

?>