<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $name; ?></title>
</head>
<body>
  <a href="<?php echo $url->set($link); ?>"><?php echo $url->set($link); ?></a>
  <?php echo $html; ?>
</body>
</html>