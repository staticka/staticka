<?php

require 'vendor/autoload.php';

$container = new Rougin\Slytherin\Container\Container;

$app = new Rougin\Staticka\Console\Application($container);

$app->run();
