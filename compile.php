<?php

require 'vendor/autoload.php';

$build = __DIR__ . '/app/build/rouginsons';

$site = __DIR__ . '/app/sites/rouginsons';

$config = new Rougin\Staticka\Config($site . '/config');

$renderer = new Rougin\Staticka\Renderer($site . '/views');

$builder = new Rougin\Staticka\Builder($config, $renderer);

$builder->build(require $site . '/routes.php', $site, $build);
