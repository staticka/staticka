# Staticka

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

A yet-another PHP-based static site generator. This converts [Markdown](https://en.wikipedia.org/wiki/Markdown) content and PHP files into static HTML. It is inspired by popular static site generators like [Hugo](https://gohugo.io) and [Jekyll](https://jekyllrb.com).

## Installation

Installing `Staticka` is possible via [Composer](https://getcomposer.org/):

``` bash
$ composer require staticka/staticka
```

## Basic Usage

The following guides below showcase the functionalities of `Staticka`. If a specified requirement wants to create a static blog site using a terminal or wants to create pages with a web-based user interface, kindly check [Console](https://github.com/staticka/console) or [Expresso](https://github.com/staticka/expresso) respectively.

### Simple HTML from string

`Staticka` can convert simple Markdown content from string into HTML:

``` php
// index.php

use Staticka\Page;
use Staticka\Parser;

// Creates a new page with the specified body -----
$page = (new Page)->setName('Hello world!');

$page->setBody("# {NAME}\nThis is a sample page.");
// ------------------------------------------------

// Converts the page into an HTML ---
echo (new Parser)->parsePage($page);
// ----------------------------------
```

``` bash
$ php index.php

<h1>Hello world!</h1>
<p>This is a sample template.</p>
```

In the example above, the `{NAME}` is a placeholder to insert the `name` value from the `Page` class to the body.

### Using `.md` files

`Staticka` also supports converting the Markdown-based files (`.md`files) by adding the path of the `.md` file to the `Page` class:

``` md
<!-- app/pages/hello-world.md -->

# Hello World!

This is a sample **Markdown** file!
```

``` php
// index.php

// ...

// Specify the path of the Markdown file -----
$file = __DIR__ . '/app/pages/hello-world.md';

$page = new Page($file);
// -------------------------------------------

// ...
```

``` bash
$ php index.php

<h1>Hello World!</h1>

<p>This is a sample <strong>Markdown</strong> file!</p>
```

### Adding Front Matter, additional data

`Staticka` supports [Front Matter](https://jekyllrb.com/docs/frontmatter) in which can add predefined variables in a specific content.

``` md
<!-- app/pages/hello-world.md -->

---
link: hello-world
---

# Hello World!

The link is **{LINK}**.
```

``` bash
$ php index.php

<h1>Hello World!</h1>
<p>The link is <strong>hello-world</strong>.</p>
```

To use the variable inside a content, use the curly brackets (`{}`) format (e.g., `{NAME}`).

### Building HTML pages

Multiple `Page` classes can be converted into `.html` files with their respective directory names using the `Site` class:

``` php
// index.php

// ...

use Staticka\Site;

// ...

// Builds the site with its pages ------------
$site = new Site($parser);

$file = __DIR__ . '/app/pages/hello-world.md';
$site->addPage(new Page($file));

$site->build(__DIR__ . '/app/web');
// -------------------------------------------
```

``` bash
$ php index.php
```

``` html
<!-- app/web/hello-world/index.html -->

<h1>Hello World!</h1>
<p>The link is <strong>hello-world</strong>.</p>
```

The `Site` class can also empty a specified directory or copy a directory with its files. This is usable if the output directory needs CSS and JS files:

```
app/
├─ web/
│  ├─ index.html
styles/
├─ index.css
```

``` php
// ...

// Empty the "output" directory ---
$output = __DIR__ . '/app/web';
$site->emptyDir($output);
// --------------------------------

// Copy the "styles" directory to "output" ---
$styles = __DIR__ . '/styles';
$site->copyDir($styles, $output);
// -------------------------------------------

// ...
```

Adding data that can be applied to all pages is also possible in the same `Site` class:

``` php
// ...

$data = array('ga_code' => '12345678');
$data['website'] = 'https://roug.in';

$site->setData($data);

// ...
```

> [!NOTE]
> The data provided in the `Site` class can also be accessed inside a content by using the curly braces format (`{}`).

### Adding template engines

Building HTML pages from Markdown files only returns the content itself. By adding a third-party template engine, it makes it easier to add partials (e.g., layouts) or provide additional styling to each page. To add a template engine, a `Render` class must be used inside the `Parser` class:

``` md
<!-- app/pages/hello-world.md -->

---
name: Hello world!
link: hello-world
plate: main.php
---

# This is a hello world!

The link is **{LINK}**. And this is to get started...
```

The `plate` property specifies the layout file to be used when parsing the page. In this example, the `main.php` is the layout to be used in the `hello-world.md` file:

``` html
<!-- app/plates/main.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $name; ?></title>
</head>
<body>
  <?php echo $html; ?>
</body>
</html>
```

> [!NOTE]
> The `$html` variable from the example above is a predefined variable for returning the content from the page that is parsed. The additional data from the `Page` class are also predefined as a variable (e.g., `$link` from `link`, `$name` from `name`).

After creating the template file (e.g., `main.php`), specify the `Parser` class to render templates using the `Render` class:

``` php
// index.php

// ...

use Staticka\Parser;
use Staticka\Render;

// ...

// Path where the "main.php" can be located ---
$path = __DIR__ . '/app/plates';
// --------------------------------------------

// Sets the Render and Parser ---
$render = new Render($path);

$parser = new Parser($render);
// ------------------------------

// Render may be added to Parser after ---
$parser->setRender($render);
// ---------------------------------------

// ...
```

Then running the script will convert the `.md` file to a compiled `.html` with the defined template:

``` bash
$ php index.php
```

``` html
<!-- app/web/hello-world/index.html -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hello world!</title>
</head>
<body>
  <h1>This is a hello world!</h1>
  <p>The link is <strong>hello-world</strong>. And this is to get started...</p></body>
</html>
```

To implement a custom template engine to `Staticka`, implement the said engine to the `RenderInterface`:

``` php
namespace Staticka\Render;

interface RenderInterface
{
    /**
     * Renders a file from a specified template.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array());
}
```

### Setting layouts

A `Layout` class allows a `Page` class to use various filters and helpers. It can also be passed as a `class-string` in the `.md` file:

``` php
// index.php

use Staticka\Layout;

// ...

$pages = __DIR__ . '/app/pages';

// Define the layout with the name "main.php" ---
$layout = (new Layout)->setName('main.php');
// ----------------------------------------------

// ...

// Set the layout into the page -------------
$page = new Page($pages . '/hello-world.md');

$site->addPage($page->setLayout($layout));
// ------------------------------------------
```

> [!NOTE]
> If a name is specified from the `Layout`, there is no need to specify the `plate` property from the `.md` file.

It is also possible to specify a `Layout` class in the `.md` file by specifying it to the `plate` property. Using this approach requires a `RenderInterface` attached to the `Parser` class:

``` php
namespace App\Layouts;

use Staticka\Layout;

class HomeLayout extends Layout
{
    /**
     * Specifies the plate to be used as the layout.
     *
     * @var string
     */
    protected $name = 'home.php';
}
```

``` md
<!-- app/pages/hello-world.md -->

---
name: Hello world!
link: hello-world
plate: App\Layouts\HomeLayout
---

# This is a hello world!

The link is **{LINK}**. And this is to get started...
```

If the `Layout` class requires complex dependencies, the `Parser` class must specify a container to easily identify the specified layout instance:

``` php
namespace App\Layouts;

use App\Complex;
use Staticka\Layout;

class ComplexLayout extends Layout
{
    protected $complex;

    public function (Complex $complex)
    {
        $this->complex = $complex;
    }

    // ...
}
```

``` php
// index.php

use App\Layouts\ComplexLayout;
use Rougin\Slytherin\Container\Container;
use App\Complex;

// ...

// Define the complex layout... ---------
$layout = new ComplexLayout(new Complex);
// --------------------------------------

// ...then add it to the container... ---
$container = new Container;

$name = ComplexLayout::class;

$container->set($name, $layout);
// --------------------------------------

// ...

// ...and set the container to the parser ---
$parser->setContainer($container);
// ------------------------------------------

// ...
```

> [!NOTE]
> If the container is not specified, the `ReflectionContainer` from [Slytherin](http://github.com/rougin/slytherin) is initialized by default.

## Extending and customization

With the philosophy of `Staticka` to be an extensible and scalable static site generator, the following guides below are use-cases for `Staticka` turning it into a content management system (CMS) or a fully functional static blog website:

### Modifying with filters

A `Filter` allows a page to be modified after being parsed:

``` php
// index.php

use Staticka\Filter\HtmlMinifier;

// ...

// Set the layout class for "main.php" ---
$layout = new Layout;

$layout->setName('main.php');
// ---------------------------------------

// Minifies the HTML after parsing the page ---
$layout->addFilter(new HtmlMinifier);
// --------------------------------------------

// ...
```

``` bash
$ php index.php
```

``` html
<!-- app/web/hello-world/index.html -->

<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Hello world!</title></head><body><h1>This is a hello world!</h1><p>The link is <strong>hello-world</strong>. And this is to get started...</p></body></html>
```

To create a custom filter, implement it using the `FilterInterface`:

``` php
namespace Staticka\Filter;

interface FilterInterface
{
    /**
     * Filters the specified code.
     *
     * @param string $code
     *
     * @return string
     */
    public function filter($code);
}
```

> [!TIP]
> Please see [FILTERS][link-filters] page for showcasing the list of available filters.

### Custom methods using helpers

A `Helper` provides additional methods inside template files:

``` php
// index.php

use Staticka\Helper\LinkHelper;

// ...

// Set the layout class for "main.php" ---
$layout = new Layout;

$layout->setName('main.php');
// ---------------------------------------

// Add a "$url" variable in templates ---
$url = new LinkHelper('https://roug.in');

$layout->addHelper($url);
// --------------------------------------

// ...
```

``` html
<!-- app/plates/main.php -->

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

``` bash
$ php index.php
```

``` html
<!-- app/web/hello-world/index.html -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hello world!</title>
</head>
<body>
  <h1>This is a hello world!</h1>
  <p>The link is <strong>hello-world</strong>. And this is to get started...</p>
  <a href="https://roug.in/hello-world">Hello world!</a>
</body>
</html>
```

To create a template helper, implement the said code in `HelperInterface`:

``` php
namespace Staticka\Helper;

interface HelperInterface
{
    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name();
}
```

> [!TIP]
> Check the [HELPERS][link-helpers] page for a list of available helpers.

### Adding filters to Parser

By design, the filters under `FilterInterface` should be executed after parsing is completed by Parser. However, there may be scenarios that the body of a page must undergo a filter prior to its parsing process:

``` php
namespace App\Filters;

use Staticka\Filter\FilterInterface;

class WorldFilter implements FilterInterface
{
    public function filter($code)
    {
        return str_replace('Hello', 'World', $code);
    }
}
```

``` php
// index.php

use App\Filters\WorldFilter;

// ...

// Replaces "Hello" string to "World" ---
$parser->addFilter(new WorldFilter);
// --------------------------------------

// ...
```

### Using the `Package` class

There may be a requirement wherein `Staticka` must be built to any PHP application such as a static blog platform or a hybrid content management system. To achieve this, the `Package` and `System` classes may be used for the specified requirement:

``` php
// index.php

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Integration\Configuration;
use Staticka\Package;

// Create paths such as "pages", "plates", and ---
// "build" from "__DIR__ . '/app'" directory -----
$root = __DIR__ . '/app';

$app = new Package($root);

$app->setPathsFromRooth();
// -----------------------------------------------


// Return the "System" instance from "Package" ---
$container = new Container;

$config = new Configuration;

$result = $app->define($container, $config);

/** @var \Staticka\System */
$app = $result->get(System::class);
// -----------------------------------------------
```

For more information on how to implement this for new ideas and projects, kindly see the codebase of [Console](https://github.com/staticka/console) and [Expresso](https://github.com/staticka/expresso) respectively for their implementations.

### Using the `PageDepot` class

`Staticka` also contains a `PageDepot` class for providing [CRUD](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete) operations for pages:

``` php
// index.php

use Staticka\Depots\PageDepot;

// ...

/** @var \Staticka\System $app */
$depot = new PageDepot($app);
```

The specified depot has a functionality to create a new page based on provided payload:

``` php
// index.php

// ...

$data = array('name' => 'Hello!');
$data['link'] = '/hello';

/** @var \Staticka\Page */
$page = $depot->create($data);

// Return the unix timestamp as its identifier ---
$id = $page->getId();
// -----------------------------------------------
```

> [!NOTE]
> A page's identifier is specified by its timestamp (e.g., `20240101000000_hello.md` returns a `1704067200` as its identifier).

After the new page is created successfully, it can now be updated its details using `update`:

``` php
// index.php

// ...

// Return the "/hello" link ---
echo $page->getLink();
// ----------------------------

$data = array('link' => '/hello-world');

/** @var \Staticka\Page */
$page = $depot->update($id, $data);

// Return the "/hello-world" link ---
echo $page->getLink();
// ----------------------------------
```

To delete a specified page, the `delete` method can be used:

``` php
// index.php

// Will delete the file specified in the page ---
$depot->delete($id);
// ----------------------------------------------
```

Finding a specified page is also possible by its identifier, by link, or by name:

``` php
// index.php

// The page can be found using its ID... ---
$page = $depot->find(1704067200);
// -----------------------------------------

// ...or by the page's link... ------
$page = $depot->findByLink('/hello');
// ----------------------------------

// ...or by its page name -----------
$page = $depot->findByName('Hello!');
// ----------------------------------
```

`PageDepot` can also return the available pages from its source directory:

``` php
// index.php

/** @var \Staticka\Page[] */
$pages = $depot->get();

// Can also return pages as data ---
$items = $depot->getAsData();
// ---------------------------------
```

> [!NOTE]
> The word `Depot` is only a preference and it is best known as the [Repository pattern](https://designpatternsphp.readthedocs.io/en/latest/More/Repository/README.html).

## Migrating to the `v0.4.0` release

The new release for `v0.4.0` will be having a [backward compatibility](https://en.wikipedia.org/wiki/Backward_compatibility) break (BC break). With this, some functionalities from the earlier versions might not be working after upgrading. This was done to increase extensibility, simplicity and maintainbility. `Staticka` is also mentioned in [my blog post](https://roug.in/hello-world-again/):

> I also want to extend this plan to my personal packages as well like [Staticka](https://github.com/staticka/staticka) and [Transcribe](https://github.com/rougin/transcribe). With this, I will introduce backward compatibility breaks to them initially as it is hard to migrate their codebase due to minimal to no documentation being provided in its basic usage and its internals. As I checked their code, I realized that they are also over engineered, which is a mistake that I needed to atone for when updating my packages in the future.

Please see [Pull Request #5](https://github.com/staticka/staticka/pull/5) for the files that were removed or updated in this release and the [UPGRADING][link-upgrading] page for the specified breaking changes.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

The unit tests of `Staticka` can be run using the `test` command:

``` bash
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/staticka/staticka/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/staticka/staticka?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/staticka.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/staticka/staticka.svg?style=flat-square

[link-build]: https://github.com/staticka/staticka/actions
[link-changelog]: https://github.com/staticka/staticka/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/staticka/staticka/contributors
[link-coverage]: https://app.codecov.io/gh/staticka/staticka
[link-downloads]: https://packagist.org/packages/staticka/staticka
[link-filters]: https://github.com/staticka/staticka/blob/master/FILTERS.md
[link-helpers]: https://github.com/staticka/staticka/blob/master/HELPERS.md
[link-license]: https://github.com/staticka/staticka/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/staticka/staticka
[link-upgrading]: https://github.com/staticka/staticka/blob/master/UPGRADING.md