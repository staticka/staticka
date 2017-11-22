<?php

namespace Rougin\Staticka\Filter;

interface FilterInterface
{
    public function filter($code);

    public function tags();
}