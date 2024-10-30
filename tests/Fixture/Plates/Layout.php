<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <?php echo $block->add('metas'); ?>
  <?php echo $block->add('styles'); ?>
</head>
<body>
  <?php echo $block->content(); ?>

  <?php echo $block->add('scripts'); ?>
</body>
</html>