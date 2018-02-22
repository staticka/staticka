# Staticka

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Staticka converts Markdown content, HTML and PHP files into static HTML.

## Install

Via Composer

``` bash
$ composer require staticka/staticka
```

## Usage

``` php
$app = new Staticka\Staticka;

$app->page('/', function ()
{
    return '# Hello world!';
});

$app->compile(__DIR__ . '/build');
```

To see the output, open the `build/index.html` in a web browser.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/staticka/staticka.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/staticka/staticka/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/staticka/staticka.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/staticka/staticka.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/staticka.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/staticka/staticka
[link-travis]: https://travis-ci.org/staticka/staticka
[link-scrutinizer]: https://scrutinizer-ci.com/g/staticka/staticka/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/staticka/staticka
[link-downloads]: https://packagist.org/packages/staticka/staticka
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors