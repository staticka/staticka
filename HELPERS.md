# Helpers

### `LayoutFilter`, `LayoutHelper`

These classes provides a helper to a template in loading layouts:

``` html
<!-- plates/main.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $title ?> - Staticka</title>
  <?= $block->add('styles') ?>
</head>
<body>
  <?= $block->content() ?>

  <script src="/index.js"></script>
  <?= $block->add('scripts') ?>
</body>
</html>
```

``` html
<!-- plates/home.php -->

<?= $layout->load('main', ['title' => $title]); ?>

<?= $block->body() ?>
  <div><?= $title ?></div>
<?= $block->end() ?>
```

After creating the specified template (e.g., `home.php`), it can be defined in the `.md` file:

``` md
<!-- pages/20241027110856_hello-world.md -->

---
name: Hello world!
link: /hello-world
plate: home <!-- this can be read as "home.php" --> 
title: Hello world!
---

Hello world!
```

## `BlockHelper`

Aside in using `LayoutFilter` and `LayoutHelper`, the `BlockHelper` can be used to create specified blocks in a template:

``` html
<!-- plates/home.php -->

<?= $layout->load('main', ['title' => $title]); ?>

<?= $block->set('styles') ?>
  <style>body { background-color: grey; }</style>
<?= $block->end() ?>

<?= $block->body() ?>
  <div>$title</div>
<?= $block->end() ?>

<?= $block->set('scripts') ?>
  <script>console.log('This is a console.text!')</script>
<?= $block->end() ?>
```

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
```

### `StringHelper`

A simple helper that contains methods for manipulating strings:

``` html
<!-- plates/home.php -->

<!-- ... -->
<p class="mb-0"><?= $str->truncate($item['body'], 200) ?></p>
<!-- ... -->
```