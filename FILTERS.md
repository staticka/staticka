# Filters

## `HtmlMinifier`

A simple filter to minify the converted HTML.

``` php
// index.php

use Staticka\Filter\HtmlMinifier;

// ...

$layout->addFilter(new HtmlMinifier);

// ...
```

## `ScriptMinifier`

Same as `HtmlMinifier`, this is a filter that minifies the inline scripts inside `<script>` tag:

``` php
// index.php

use Staticka\Filter\ScriptMinifier;

// ...

$layout->addFilter(new ScriptMinifier);

// ...
```

## `StyleMinifier`

If `ScriptMinifier` is for `<script>` tags, this filter is only applicable to `<style>` tags:

``` php
// index.php

use Staticka\Filter\StyleMinifier;

// ...

$layout->addFilter(new StyleMinifier);

// ...
```