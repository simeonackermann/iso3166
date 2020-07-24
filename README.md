# ISO3166 multi language

Fork of [thephpleague/iso3166](https://github.com/thephpleague/iso3166) PHP library providing ISO 3166-1 country data with support of multiple languages.

Currently supported languages: English, German, France, Russian, Arabic

## Installing

``` sh
$ composer require simeonackermann/iso3166
```

## Using

Quick guide with custom language:

``` php
$ISO3166 = new League\ISO3166\ISO3166;
$ISO3166->setLanguage('de');
$data = $ISO3166->alpha3('nld');
```

Data sample:

``` php
[
    'name' => 'Niederlande',
    'alpha2' => 'NL',
    'alpha3' => 'NLD',
    'numeric' => '528'
]
```

All available languages:
```php
// en, de, fr, ru, ar, ...
$languages = (new League\ISO3166\ISO3166)->getLanguages();

```

You still can use all methods with default language:

``` php
$data = (new League\ISO3166\ISO3166)->name($name);
$data = (new League\ISO3166\ISO3166)->alpha2($alpha2);
$data = (new League\ISO3166\ISO3166)->alpha3($alpha3);
$data = (new League\ISO3166\ISO3166)->numeric($numeric);
```

The currency (part of the origin library) is currently not available.

See also [iso3166.thephpleague.com/using](https://iso3166.thephpleague.com/using) section of the origin documentation.

## What is ISO 3166-1

> ISO 3166-1 is part of the ISO 3166 standard published by the International Organization for Standardization (ISO), and defines codes for the names of countries, dependent territories, and special areas of geographical interest. The official name of the standard is Codes for the representation of names of countries and their subdivisions – Part 1: Country codes. It defines three sets of country codes:
> * ISO 3166-1 alpha-2 – two-letter country codes which are the most widely used of the three, and used most prominently for the Internet's country code top-level domains (with a few exceptions).
> * ISO 3166-1 alpha-3 – three-letter country codes which allow a better visual association between the codes and the country names than the alpha-2 codes.
> * ISO 3166-1 numeric – three-digit country codes which are identical to those developed and maintained by the United Nations Statistics Division, with the advantage of script (writing system) independence, and hence useful for people or systems using non-Latin scripts.
>
> *-- [Wikipedia](http://en.wikipedia.org/wiki/ISO_3166-1)*

## Contributing

Feel free to submit a pull request or create an issue.

## License

league/iso3166 is licensed under the MIT license.

## Source(s)

* [ISO 3166-1](http://en.wikipedia.org/wiki/ISO_3166-1) by [Wikipedia](http://www.wikipedia.org) licensed under [CC BY-SA 3.0 Unported License](http://en.wikipedia.org/wiki/Wikipedia:Text_of_Creative_Commons_Attribution-ShareAlike_3.0_Unported_License)
* [www.iso.org](http://www.iso.org)
