<?php
  require('inc/common.inc.php');

  $errorList = array();

  function check_pin()
  {
    global $pins;
    if (isset($_POST['pin'])
        && in_array(hash('sha256', $_POST['pin']), $pins) === TRUE) {
      return true;
    }
    return false;
  }

  function upload_image($file, $expires)
  {
    $uploadPath = get_image_bucket($expires);
    $newImage = uniqid().'.'.pathinfo($file['name'], PATHINFO_EXTENSION);
    if (move_uploaded_file($file['tmp_name'], $uploadPath.$newImage) !== FALSE) {
      return $newImage;
    } else {
      $errorList[] = 'An error occured while moving the file to the destination folder: Please tell the admin to check all his chowns and chmods. He\'ll know what you mean..';
      return false;
    }
  }

  function is_valid_image($name)
  {
    $isValidImage = false;
    if (isset($_FILES[$name])) {
      $file = $_FILES[$name];
      if ($file['name'] == '' || is_uploaded_file($file['tmp_name']) === FALSE || $file['error'] != UPLOAD_ERR_OK) {
        $errorList[] = 'An internal error occured while uploading the file (Means: I really don\'t know what happend here)';
      } else if (in_array($file['type'], array('image/gif', 'image/png', 'image/jpeg')) === FALSE) {
        $errorList[] = 'This image format is not allowed. It\'s either GIF or PNG or JPEG.';
      } else if ((int)$file['size'] > MAX_IMAGE_SIZE) {
        $errorList[] = 'Image is too big. Re-read the limitations, then try again.';
      } else {
        $isValidImage = true;
      }
    }
    return $isValidImage;
  }

  if (isset($_POST['upload_image']) && isset($_POST['expires'])) {
    $expires = $_POST['expires'];
    if (REQUIRE_PIN && check_pin() === FALSE) {
      $errorList[] = 'Invalid pin';
    } else if ($expires != 'd'
               && $expires != 'm'
               && $expires != 'y'
               && $expires != 'u') {
      $errorList[] = 'Tampering with form data is not allowed here.';
    } else {
      if (is_valid_image('upload_image')) {
        $imageName = upload_image($_FILES['upload_image'], $expires);
        if ($imageName !== FALSE) {
          header('location: '.SCRIPT_DIR.$expires.'/'.$imageName);
        }
      }
    }
  }
?><!doctype html>
<html lang="de" dir="ltr">
<head>
  <meta charset="utf-8">
  <link href="//fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
  <link href="res/css/style.css" rel="stylesheet" type="text/css" media="screen">
  <title>Quick Image Upload</title>
</head>
<body>
<div class="page-wrapper">
  <h1 class="page-title">
    ಠ_ರೃ Quick image upload&hellip;<br>
    <span class="page-sub-title">&hellip;just that.</span>
  </h1>
  <?php if (!empty($errorList)) : ?>
    <div class="message message-error">
      <ul class="error-list">
      <?php foreach ($errorList as $e) : ?>
        <li><?php echo $e; ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <form class="upload-form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <p>
      <label for="upload_image" class="label-left">File:</label>
      <input type="file" id="upload_image" name="upload_image"><br>
      <span class="helper">
        Supported file types: jpg, jpeg, png, gif <br>
        Max file size: <?php echo MAX_IMAGE_SIZE / 1024 / 1024; ?> MB
      </span>
    </p>
    <?php if (REQUIRE_PIN) : ?>
    <p>
      <label for="pin" class="label-left">PIN:</label>
      <input id="pin" name="pin" type="password" size="20" placeholder="1234">
    </p>
    <?php endif; ?>
    <fieldset>
      This image should be deleted in
      <ul class="radiogroup">
        <li><input type="radio" id="exp_day"   name="expires" value="d" checked><label class="label-right" for="exp_day">a day</label></li>
        <li><input type="radio" id="exp_month" name="expires" value="m"><label class="label-right" for="exp_month">a month</label></li>
        <li><input type="radio" id="exp_year"  name="expires" value="y"><label class="label-right" for="exp_year">a year</label></li>
        <li><input type="radio" id="exp_none"  name="expires" value="u"><label class="label-right" for="exp_none">the year 2525</label></li>
      </ul>
    </fieldset>
    <p>
      <button type="submit" name="upload_image">Upload</button>
    </p>
  </form>
</div>
</body>
</html>
