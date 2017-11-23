<?php

namespace Rougin\Staticka\Filter;

class CssMinifier extends \MatthiasMullie\Minify\CSS implements FilterInterface
{
    public function filter($code)
    {
        $this->add($code);

        return $this->minify();
    }

    public function rename($filename)
    {
        return str_replace('.css', '.min.css', $filename);
    }

    public function tags()
    {
        return array('css');
    }
}
