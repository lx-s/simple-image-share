<?php
  require('conf.inc.php');

  if (IN_DEVELOPMENT === FALSE &&
      (!isset($_GET['secret']) || $_GET['secret'] != CRON_SECRET)) {
    header('HTTP/1.0 401 Unauthorized');
    exit;
  }

  function get_expired_files($path, $expiration, &$expFiles) {
    if (is_dir($path) && ($dh = opendir($path)) !== FALSE) {
      $now = time();
      while (($file = readdir($dh)) !== FALSE) {
        if ($file != '.' && $file != '..') {
          $filePath = $path.$file;
          $fileTime = filemtime($filePath);
          if (($now - $fileTime) > $expiration) {
            $expFiles[] = $filePath;
          }
        }
      }
      closedir($dh);
    }
  }

  $expDay   = 24 * 60 * 60;
  $expMonth = $expDay * 31;
  $expYear  = $expMonth * 12;

  $expFiles = array();
  get_expired_files(IMG_DIR_DAY,   $expDay,   $expFiles);
  get_expired_files(IMG_DIR_MONTH, $expMonth, $expFiles);
  get_expired_files(IMG_DIR_YEAR,  $expYear,  $expFiles);

  echo '<p>Removing files...</p><ol>';
  foreach ($expFiles as $file) {
    echo '<li>'.$file.'</li>';
    unlink($file);
  }
  echo '</ol><p>...Done!</p>';

