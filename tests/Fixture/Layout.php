<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <style>
    body {
      background: #fff;
    }
  </style>
</head>
<body>
  <a href="<?php echo $url->set('home') ?>">Home</a>
  <?php echo $content; ?>
</body>
</html>