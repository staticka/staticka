# Staticka

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Yet another static site generator for PHP.

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
   ├── hello-world/
   │   └── index.html
   └── index.html
```

#### Command Options

* `--site` - path of the source files. If not specified, it will use the current working directory as its default.
* `--path` - path on which the static files will be built. If not specified, the current working directory will be used.

### Customization

To customize the directory structure of the static site, add a file named `staticka.php` to specify the paths of the required files/directories:

``` php
return array(
    /**
     * Directory path for the configurations.
     *
     * @var string
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
     * @var array
     */
    'routes' => __DIR__ . '/routes.php',
);
```

**NOTE:** You could also define an array of `routes` in `staticka.php` instead on specifying it to a `routes.php` file. You can do also the same thing in `config`.

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

## Inspirations

* [Awesome PHP!](https://github.com/ziadoz/awesome-php) by [Jamie York](https://github.com/ziadoz)
* [Codeigniter](https://codeigniter.com) by [EllisLab](https://ellislab.com)/[British Columbia Institute of Technology](http://www.bcit.ca)
* [Crux](https://github.com/yuloh/crux) by [Matt A](https://github.com/yuloh)
* [Fucking Small](https://github.com/trq/fucking-small) by [Tony Quilkey](https://github.com/trq)
* [Laravel](https://laravel.com) by [Taylor Otwell](https://github.com/taylorotwell)
* [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial) by [Patrick Louys](https://github.com/PatrickLouys)
* [PHP Design Patterns](http://designpatternsphp.readthedocs.org/en/latest) by [Dominik Liebler](https://github.com/domnikl)
* [PHP Standard Recommendations](http://www.php-fig.org/psr) by [PHP-FIG](http://www.php-fig.org)
* [Symfony](http://symfony.com) by [SensioLabs](https://sensiolabs.com)

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
