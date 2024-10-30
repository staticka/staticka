The following are the breaking changes introduced in `v0.4.0`. As previously mentioned, this was done to improve its extensibility, simplicity and maintainbility. Please see the `"Breaking bad" packages` in [my blog post](https://roug.in/hello-world-again/) for the said reason:

## Replace code marked as `@deprecated`

Prior in updating `Staticka` to `v0.4.0`, kindly check to replace any code that were marked as `@deprecated`. The specified `@deprecated` code can also be highlighted if using an Integrated Development Environment (IDE):

``` php
namespace Staticka;

use Staticka\Parser;
use Staticka\Contracts\BuilderContract;

/**
 * @deprecated since ~0.4, use "Staticka\Parser" instead.
 *
 * // ...
 */
class Builder extends Parser implements BuilderContract
```

> [!WARNING]
> This is required as the `v0.4.0` release will remove all `@deprecated` code.

## Change `PageFactory` to `Page`

**Before**

``` php
use Staticka\Factories\PageFactory;

$page = new PageFactory;

// Load the file as a Page class ----------
$page = $page->file(__DIR__ . '/index.md');
// ----------------------------------------
```

**After**

``` php
use Staticka\Page;

// Load the file as a Page class -------
$page = new Page(__DIR__ . '/index.md');
// -------------------------------------
```

## Remove `PageContract` properties

**Before**

``` php
// Content of the page
PageContract::DATA_BODY = 'body';

// Specific name of the page
PageContract::DATA_NAME = 'name';

// The URL path of the page
PageContract::DATA_LINK = 'link';

// File path of the template
PageContract::DATA_PLATE = 'plate';

// Title heading for the page
PageContract::DATA_TITLE = 'title';
```

**After**

``` php
use Staticka\Page;

$page = new Page;

$page->setName('Hello world!');
$page->setBody("# {NAME}\nThis is a sample page.");

// ...
```

In this release, the `PageContract` is removed and will be replaced by methods (e.g., `setName`, `setBody`, etc.). When using [Front Matter](https://jekyllrb.com/docs/frontmatter) content inside an `.md` file, `Staticka` will use the following properties for the `Parser` class:

### `name`

Specifies the name of the page. This will replace `PageContract::DATA_NAME` and `PageContract::DATA_TITLE`.

### `link`

The property to be used in building the specified page. This replaces `PageContract::DATA_LINK`.

### `plate`

The template to be used if a `Render` class is specified. This property will replace `PageContract::DATA_PLATE`.

## Change `Website` to `Site`

**Before**

``` php
use Staticka\Website;

$site = new Website;

// ...

$site->add($page);

$site->build(__DIR__ . '/public');
```

**After**

``` php
use Staticka\Site;

$site = new Site;

// ...

$site->addPage($page);

$site->build(__DIR__ . '/public');
```

## Replace `Layout::filter` with `Layout::addFilter`

**Before**

``` php
use Staticka\Filter\HtmlMinifier;
use Staticka\Layout;

$layout = new Layout;

$layout->filter(new HtmlMinifier);
```

**After**

``` php
use Staticka\Filter\HtmlMinifier;
use Staticka\Layout;

$layout = new Layout;

$layout->addFilter(new HtmlMinifier);
```

## Replace `Layout::helper` with `Layout::addHelper`

**Before**

``` php
use Staticka\Helper\LinkHelper;
use Staticka\Layout;

$layout = new Layout;

$link = 'https://roug.in/';
$helper = new LinkHelper($link);
$layout->helper($helper);
```

**After**

``` php
use Staticka\Helper\LinkHelper;
use Staticka\Layout;

$layout = new Layout;

$link = 'https://roug.in/';
$helper = new LinkHelper($link);
$layout->addHelper($helper);
```

## Change adding of `Layout` to `Page`

**Before**

``` php
use Staticka\Factories\PageFactory;
use Staticka\Layout;

$layout = new Layout;

$page = new PageFactory($page);

// ...
```

**After**

``` php
use Staticka\Layout;
use Staticka\Page;

$layout = new Layout;

$page = new Page;

$page->setLayout($layout);

// ...
```