<?php

namespace Rougin\Staticka\Filter;

class HtmlMinifier extends \voku\helper\HtmlMin implements FilterInterface
{
    public function __construct()
    {
        $this->doOptimizeViaHtmlDomParser();
        $this->doRemoveComments();
        $this->doSumUpWhitespace();
        $this->doRemoveWhitespaceAroundTags();
        $this->doOptimizeAttributes();
        $this->doRemoveHttpPrefixFromAttributes();
        $this->doRemoveDefaultAttributes();
        $this->doRemoveDeprecatedAnchorName();
        $this->doRemoveDeprecatedScriptCharsetAttribute();
        $this->doRemoveDeprecatedTypeFromScriptTag();
        $this->doRemoveDeprecatedTypeFromStylesheetLink();
        $this->doRemoveEmptyAttributes();
        $this->doRemoveValueFromEmptyInput();
        $this->doSortCssClassNames();
        $this->doSortHtmlAttributes();
        $this->doRemoveSpacesBetweenTags();
    }

    public function filter($code)
    {
        return trim(preg_replace('/\s+/', ' ', $this->minify($code)));
    }

    public function rename($filename)
    {
        return $filename;
    }

    public function tags()
    {
        return array('htm', 'html');
    }
}
