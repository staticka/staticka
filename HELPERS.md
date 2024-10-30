# Helpers

## `LinkHelper`

Provides a method for creating a URL with its specified URI:

``` php
// index.php

$helper = new LinkHelper('https://roug.in');
```

``` html
<!-- plates/main.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $name; ?></title>
</head>
<body>
  <?php echo $html; ?>
  <a href="<?php echo $url->set($link); ?>"><?php echo $name; ?></a>
</body>
</html>
```

### `PlateHelper`

One of the helpers that allows to load sub-templates (template inside a template):

``` html
<nav>
  <div>
    <div>Sample website</div>
    <div>
      <ul>
        <li>Blog</li>
        <li>Works</li>
        <li>Me</li>
      </ul>
    </div>
  </div>
</nav>
```

``` html
<!-- plates/home.php -->

<?= $layout->load('main', ['title' => $title]); ?>

<?= $block->body() ?>
  <?= $plate->add('navbar', compact('category', 'link', 'name')) ?>

  <!-- ... -->
<?= $block->end() ?>