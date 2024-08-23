<?php

namespace Staticka\Filter;

use Staticka\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HtmlMinifierTest extends Testcase
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var \Staticka\Contracts\FilterContract
     */
    protected $filter;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        /** @var string */
        $name = str_replace('Filter', 'Fixture', __DIR__);

        $this->filter = new HtmlMinifier;

        /** @var string */
        $code = file_get_contents($name . '/Sample.html');

        $this->code = $code;
    }

    /**
     * @return void
     */
    public function test_filter_from_html()
    {
        $expected = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Document</title><style>
    body
    {
      background: #fff;
    }
  </style></head><body><h1>Brave World</h1><code class="language-apache">#
# Note that from this point forward you must specifically allow
# particular features to be enabled - so if something\'s not working as
# you might expect, make sure that you have specifically enabled it
# below.
#

AddHandler application/x-httpd-php .php
AddType application/x-httpd-php .php .html0
LoadModule PHP_MODULE</code><p><strong>Lorem ipsum dolor sit amet, consectetur adipisicing elit</strong>. Architecto fugit voluptate obcaecati quidem tenetur <b>consequatur incidunt</b> optio sit est illum accusantium laudantium necessitatibus, saepe nobis enim tempore magnam, eius mollitia.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat animi et, vitae minus. Fugit alias dolorum minima facilis tempore, quod minus, voluptas autem, harum illo aperiam consequuntur amet? Rerum, veritatis.</p><p><a href="https://lipsum.com/feed/html">Lorem ipsum dolor.</a></p><div class="container"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae sapiente esse quae est natus beatae sint eius unde ex, repellendus quas sunt, ratione excepturi minus reprehenderit error deleniti non. Vitae!</p></div><textarea name="content"># Staticka

Staticka is a simple and extensible static site generator written in PHP. It converts Markdown content and PHP template files into static HTML. This library was heavily inspired in popular static site generators such as Hugo and Jekyll.

``` php
require \'vendor/autoload.php\';

$website = new Staticka\Website;

// Creates a new &quot;Hello World&quot; page
$website-&gt;page(\'# Hello World\');

// Compiles the pages to the &quot;build&quot; directory
$website-&gt;compile(__DIR__ . \'/build\');
```</textarea><code class="language-php">// Foo.php

class Foo
{
    public function baz()
    {
        // ...
    }
}

// Bar.php

class Bar
{
    protected $foo;

    public function __construct(Foo $foo)
    {
        $this-&gt;foo = $foo;
    }

    public function booz()
    {
        return $this-&gt;foo-&gt;baz();
    }
}</code></body></html>';

        $result = $this->filter->filter($this->code);

        $result = str_replace("\r", '', $result);

        $result = str_replace('&#039;', '\'', $result);

        $expected = str_replace("\r", '', $expected);

        $this->assertEquals($expected, $result);
    }
}
