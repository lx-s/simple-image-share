<?php
  require 'inc/common.inc.php';

  function exit_bad_request() {
    header('HTTP/1.0 400 Bad Request');
    echo '<h1>400 Bad Request</h1>';
    exit;
  }

  function exit_not_found() {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>404 Not Found</h1><p>The image you requested could not be found.</p>';
    exit;
  }

  function exit_not_implemented() {
    header('HTTP/1.0 501 Not Implemented');
    echo '<h1>505 Not Implemented</h1>';
    exit;
  }

  function output_image($path, $type) {
    $contentType = 'Content-Type: image/';
    if ($type == IMAGETYPE_GIF) {
      $contentType .= 'gif';
    } else if ($type == IMAGETYPE_JPEG) {
      $contentType .= 'jpeg';
    } else if ($type == IMAGETYPE_PNG) {
      $contentType .= 'png';
    } else {
      exit_not_implemented();
    }
    $fileSize = intval(sprintf('%u', filesize($path)));
    $chunkSize = 1 * (1024 * 1024); // how many megabytes per chunk

    header($contentType);
    if ($fileSize > $chunkSize) {
       if (($fh = @fopen($path, 'rb')) !== FALSE) {
        while (!feof($fh)) {
           $buffer = fread($fh, $chunkSize);
           echo $buffer;
           ob_flush();
           flush();
        }
        fclose($fh);
      } else {
        exit_not_found();
      }
    } else if (@readfile($path) === FALSE) {
      exit_not_found();
    }
  }

  // ---------------------------------------------------------------------------

  $image  = isset($_GET['i']) ? $_GET['i'] : exit_bad_request();
  $bucket = isset($_GET['b']) ? $_GET['b'] : exit_bad_request();

  $imgPath = get_image_bucket($bucket);
  if ($imgPath === FALSE) {
    exit_bad_request();
  }
  $imgPath .= basename($image);

  if (($type = @exif_imagetype($imgPath)) !== FALSE) {
    output_image($imgPath, $type);
  } else {
    exit_not_found();
  }