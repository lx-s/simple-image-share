<?php
  require 'conf.inc.php';

  function get_image_bucket($expires) {
    if ($expires == 'd') {
      return IMG_DIR_DAY;
    } else if ($expires == 'm') {
      return IMG_DIR_MONTH;
    } else if ($expires == 'y') {
      return IMG_DIR_YEAR;
    } else if ($expires == 'u') {
      return IMG_DIR_UNLIMITED;
    } else {
      return false;
    }
  }
