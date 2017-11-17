<?php

use Rougin\Staticka\Route;

$routes = array();

$routes[] = new Route('/', 'hello-world');
$routes[] = new Route('/hello-world', 'hello-world');

return $routes;
