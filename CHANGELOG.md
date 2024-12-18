# Changelog

All notable changes to `Staticka` will be documented in this file.

## [0.4.0](https://github.com/staticka/staticka/compare/v0.3.0...v0.4.0) - 2024-11-10

> [!WARNING]
> This release will introduce a backward compatability break if upgrading from `v0.3.0` release.

### Added
- `Package`, `System` for easily extending `Staticka` 
- `PSR-11` (`ContainerInterface`) via `rougin/slytherin`

### Changed
- Code coverage to `Codecov`
- Code documentation by `php-cs-fixer`
- Improved code quality by `phpstan`
- Simplified code structure
- Workflow to `Github Actions`
- `ViewHelper` to `PlateHelper`

## [0.3.0](https://github.com/staticka/staticka/compare/v0.2.1...v0.3.0) - 2020-05-03

### Added
- `Builder` for building `Page` instances to HTML
- `Contracts` for implementing Staticka classes
- `Factories` for creation of Staticka instances

### Changed
- `Layout` implemented as `LayoutContract`
- `Page` implemented as `PageContract`
- `Website` implemented as `WebsiteContract`

### Deprecated
- `Content` (replaced by `BuilderContract`)
- `Filter` (renamed as `Filters`)
- `Helper` (renamed as `Helpers`)
- `Matter`
- `Website::compile` (replaced by `Website::build`)
- `Website::page` (replaced by `Website::add`)
- `Website::transfer` (replaced by `Website::copy`)

## [0.2.1](https://github.com/staticka/staticka/compare/v0.2.0...v0.2.1) - 2019-01-12

### Changed
- `Filter\HtmlMinifier`
- `Filter\ScriptMinifier`
- `Filter\StyleMinifier`

## [0.2.0](https://github.com/staticka/staticka/compare/v0.1.0...v0.2.0) - 2018-04-07

### Added
- `Filter\ScriptMinifier`
- `Filter\StyleMinifier`
- `Helper\ViewHelper`

### Fixed
- Error in transferring files with directories 

## 0.1.0 - 2018-04-04

### Added
- `Staticka` library