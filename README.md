# AttributeUnits

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coverall]][link-coveralls]

A simple Magento 2 module that adds a text field `Attribute unit` to the tab `Storefront Properties` of
`Attribute Information` in the admin area. The content of this text field will be added after the formated value of the
attribute, when this attribute is rendered in frontend.
For example the value `12345.6` with the unit `mm` will be rendered to `12.345,60 mm` in german stores or `12,345.60 mm`
in english stores.

## Install

* Add the repository to the repositories section of your composer.json file
```
 "repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:PartBerlin/AttributeUnits.git"
    }
 ],
```
* Require the module & install
```
 composer require partberlin/module-attributeunits:dev-master
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email info@part-online.de instead of using the issue tracker.

## Credits

- [PART][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/github/license/PartBerlin/AttributeUnits.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/PartBerlin/AttributeUnits/master.svg?style=flat-square
[ico-coverall]: https://img.shields.io/coveralls/PartBerlin/AttributeUnits.svg?style=flat-square

[link-travis]: https://travis-ci.org/PartBerlin/AttributeUnits
[link-coveralls]: https://coveralls.io/github/PartBerlin/AttributeUnits
[link-author]: https://part.berlin/
[link-contributors]: ../../contributors
