<?php
  define('IN_DEVELOPMENT', false);

  define('SCRIPT_DIR', '/');

  define('CRON_SECRET', 'yoursecret');
  define('REQUIRE_PIN', true);

  $pins = array(/* array of sha 256 hashes */);

  define('IMG_DIR', dirname(__FILE__).'/../data/');
  define('IMG_DIR_DAY', IMG_DIR.'day/');
  define('IMG_DIR_MONTH', IMG_DIR.'month/');
  define('IMG_DIR_YEAR', IMG_DIR.'year/');
  define('IMG_DIR_UNLIMITED', IMG_DIR.'unlimited/');

  define('MAX_IMAGE_SIZE', 50 * 1024 * 1024); // 50 MB
