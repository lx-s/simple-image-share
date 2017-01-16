<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Generate Password Hash</title>
  <style type="text/css">
    pre {
      border-left:  2px solid #888;
      padding: 6px 0 6px 4px;
      font-family: monospace;
    }
  </style>
</head>
<body>
<?php
  if (isset($_POST['hashit'])) {
    $rawPass = isset($_POST['rawPass']) ? $_POST['rawPass'] : '';
    $rawPassHash = hash('sha256', $rawPass);
    echo '<h1>Your password:</h1><pre><code>$pins = array(\''.$rawPassHash.'\',);</code></pre>';
  }
?>
  <h1>Generate Password Hash</h1>
  <form method="post" accept-charset="utf-8">
    <p>
      <label for="rawPass">Password:</label>
      <input id="rawPass" name="rawPass" type="password" placeholder="Password here">
    </p>
    <p>
      <input type="submit" name="hashit" value="Hash it!">
    </p>
  </form>
</body>
</html>