# Staticka

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Staticka is yet another static site generator written in PHP. It uses [`Markdown`](https://en.wikipedia.org/wiki/Markdown) format for the content files and the [`Laravel Blade`](https://laravel.com/docs/5.5/blade) engine for managing the view files. It is also written using [Slytherin](https://github.com/rougin/slytherin).

## Install

Via Composer

``` bash
$ composer require rougin/staticka
```

## Usage

### Preparation

To generate a simple static site, you must have at least this file structure:

```
static-site/
├── content/
│   └── hello-world.md
├── views/
│   └── index.blade.php
└── routes.php
```

The contents of the `routes.php` file must return an array of `Rougin\Staticka\Route` instances:

``` php
$routes = array();

array_push($routes, new Rougin\Staticka\Route('/', 'hello-world'));
array_push($routes, new Rougin\Staticka\Route('/hello-world', 'hello-world'));

return $routes;
```

There is a third parameter in the `Route` class wherein you could specify the view it should use.
It maps to the `index` file by default (the `index.blade.php`) from the `views` directory.

**NOTE:** `/` is a special character that means it is the landing page of the site.

### Building

To build your static site, you need to run this command:

``` bash
$ vendor/bin/staticka build --site="static-site" --path="static-site/build"
```

After running the command, it will render all routes listed in `routes.php` and it should look like this:

```
static-site/
└── build/
│  ├── hello-world/
│  │   └── index.html
│  └── index.html
└── content/
```

#### Command Options

* `--site` - path of the source files. If not specified, it will use the current working directory as its default.
* `--path` - path on which the static files will be built. If not defined, the current working directory or the `--site` + `build` directory (if specified) will be used.

### Customization

To customize the directory structure of the static site, add a file named `staticka.php` to specify the paths of the required files/directories:

``` php
return array(
    /**
     * Directory path for the configurations.
     *
     * @var array|string
     */
    'config' => __DIR__ . '/config',

    /**
     * Directory path for the contents.
     *
     * @var string
     */
    'content' => __DIR__ . '/content',

    /**
     * Directory path for the views.
     *
     * @var string
     */
    'views' => __DIR__ . '/source/views',

    /**
     * A listing of available routes.
     *
     * @var array|string
     */
    'routes' => __DIR__ . '/routes.php',
);
```

**NOTE:** You could also define an array of `routes` in `staticka.php` instead on specifying it to a `routes.php` file. You can do also the same thing in `config`.

### Watch file/s for changes

To run the `build` command if there is a file change, you need to run this command:

``` bash
$ vendor/bin/staticka watch --site="static-site" --path="static-site/build"
```

By default, Staticka will watch the keys specified from the settings file (`staticka.php`):

* `config`
* `content`
* `views`

If you want to add additional folders to be watched, add `watch` key in `staticka.php`:

``` php
    ...

    /**
     * Looks for file changes from the specified directories.
     *
     * @var array
     */
    'watch' => array(__DIR__ . '/source/assets'),

    ...
```

### Run scripts before or after building

You may encounter a scenario wherein you need to compile your files first before or after building. An example for this one is running [Gulp](https://gulpjs.com/) commands. To run the required scripts, include a `scripts` key with `before` and `after` array in the `staticka.php`:

``` php
    ...

    /**
     * Scripts needed to be run before or after the building.
     *
     * @var array
     */
    'scripts' => array('before' => 'gulp'),

    ...
```

### Helpers

Staticka can use helpers in helping you to put functions into your view files. By default, `Rougin\Staticka\Helper\UrlHelper` is added in which can be used in the view by the `$url` variable. To add additional helpers to your site, include them in a `includes` key in the `staticka.php` file.

``` php
    ...

    /**
     * Returns a listing of helpers to be used in the view files.
     *
     * @var array
     */
    'filters' => array(
        /**
         * The "key" is the alias to the view while the "value" is the class related to it.
         */
        'url' => 'Rougin\Staticka\Helper\UrlHelper',
        'array' => 'Acme\Helper\ArrayHelper',
        'dir' => 'Acme\Helper\DirectoryFilter',
    ),

    ...
```

### Filters

Staticka can also use filters to modify the contents generated in the built path. By default, these two filters are available and added:

* `Rougin\Staticka\Filter\CssMinifier`
* `Rougin\Staticka\Filter\HtmlMinifier`

To modify the list of filters you want to use, just add a `filters` key in `staticka.php` file:

``` php
    ...

    /**
     * Returns a listing of filters to be used in the built site.
     *
     * @var array
     */
    'filters' => array(
        'Rougin\Staticka\Filter\CssMinifier',
        'Rougin\Staticka\Filter\HtmlMinifier',
        'Acme\Filter\MyFilter',
        'Acme\Filter\AnotherFilter',
    ),

    ...
```

To create your own filter, you must implement it in [`FilterInterface`](src/Filter/FilterInterface.php).

### Integrations

If you have a filter or a helper that contains dependencies or needs to be configured first, it is better to add them into an integration in order for Staticka to use it properly. The example below contains the integration of the `Rougin\Staticka\Helper\UrlHelper`:

``` php
namespace Rougin\Staticka\Helper;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Helper Integration
 *
 * An integration for template renderers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HelperIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $url = new UrlHelper($config->get('app.base_url'));

        $container->set('Rougin\Staticka\Helper\UrlHelper', $url);

        return $container;
    }
}
```

To include your customized integration, add an `integration` key in the `staticka.php` file:

``` php
    ...

    /**
     * Returns a listing of integrations to be used.
     *
     * @var array
     */
    'integrations' => array(
        'Acme\Integrations\AcmeIntegration',
        'Acme\Integrations\NewIntegration',
        'Rougin\Staticka\Content\MarkdownIntegration',
        'Rougin\Staticka\Helper\HelperIntegration',
        'Rougin\Weasley\Integrations\Illuminate\ViewIntegration',
    ),

    ...
```

**NOTE**: `MarkdownIntegration` and `ViewIntegration` are implemented in `Rougin\Staticka\Content\ContentInterface` and `Rougin\Slytherin\Template\RendererInterface` respectively which are required by Staticka to generate the content and view files. So if you don't want to use the [`Markdown`](https://en.wikipedia.org/wiki/Markdown) format and the [`Laravel Blade`](https://laravel.com/docs/5.5/blade) engine, you can replace them by implementing it to their mentioned interfaces.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/staticka.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/staticka/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/staticka.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/staticka.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/staticka.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/staticka
[link-travis]: https://travis-ci.org/rougin/staticka
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/staticka/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/staticka
[link-downloads]: https://packagist.org/packages/rougin/staticka
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors
