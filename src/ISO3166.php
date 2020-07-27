<?php

/*
 * (c) Simeon Ackermann
 * (c) Rob Bast <rob.bast@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Donsi\ISO3166;

use Donsi\ISO3166\Exception\DomainException;
use Donsi\ISO3166\Exception\OutOfBoundsException;

final class ISO3166 implements \Countable, \IteratorAggregate, ISO3166DataProvider
{
    /** @var string */
    const KEY_ALPHA2 = 'alpha2';
    /** @var string */
    const KEY_ALPHA3 = 'alpha3';
    /** @var string */
    const KEY_NUMERIC = 'numeric';
    /** @var string */
    const KEY_NAME = 'name';
    /** @var string[] */
    private $keys = [self::KEY_ALPHA2, self::KEY_ALPHA3, self::KEY_NUMERIC, self::KEY_NAME];
    /**  @var string[] */
    private $languages = ['en', 'de', 'fr', 'ru', 'ar'];
    /** @var string */
    private $language = 'en';

    /**
     * @param array[] $countries replace default dataset with given array
     * @todo Given countries will be overwritten if we call setLanguage()
     */
    public function __construct(array $countries = [])
    {
        $this->getCountriesData();

        if ($countries) {
            $this->countries = $countries;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function name($name)
    {
        Guards::guardAgainstInvalidName($name);

        return $this->lookup(self::KEY_NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function alpha2($alpha2)
    {
        Guards::guardAgainstInvalidAlpha2($alpha2);

        return $this->lookup(self::KEY_ALPHA2, $alpha2);
    }

    /**
     * {@inheritdoc}
     */
    public function alpha3($alpha3)
    {
        Guards::guardAgainstInvalidAlpha3($alpha3);

        return $this->lookup(self::KEY_ALPHA3, $alpha3);
    }

    /**
     * {@inheritdoc}
     */
    public function numeric($numeric)
    {
        Guards::guardAgainstInvalidNumeric($numeric);

        return $this->lookup(self::KEY_NUMERIC, $numeric);
    }

    /**
     * @return array[]
     */
    public function all()
    {
        return $this->countries;
    }

    /**
     * @param string $key
     *
     * @throws \Donsi\ISO3166\Exception\DomainException if an invalid key is specified
     *
     * @return \Generator
     */
    public function iterator($key = self::KEY_ALPHA2)
    {
        if (!in_array($key, $this->keys, true)) {
            throw new DomainException(sprintf(
                'Invalid value for $indexBy, got "%s", expected one of: %s',
                $key,
                implode(', ', $this->keys)
            ));
        }

        foreach ($this->countries as $country) {
            yield $country[$key] => $country;
        }
    }

    /**
     * @see \Countable
     *
     * @internal
     *
     * @return int
     */
    public function count()
    {
        return count($this->countries);
    }

    /**
     * @see \IteratorAggregate
     *
     * @internal
     *
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->countries as $country) {
            yield $country;
        }
    }

    /**
     * Get available languages
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set current languages
     *
     * @param string $language
     * @return void
     */
    public function setLanguage($language)
    {
        Guards::guardAgainstInvalidLanguage($language);

        $language = strtolower($language);

        if (!in_array($language, $this->getLanguages())) {
            throw new DomainException(sprintf(
                'Given language "%s" not available. Expected one of: %s',
                $language,
                implode(', ', $this->getLanguages())
            ));
        }

        $this->language = $language;
        $this->getCountriesData();
    }

    /**
     * Get languages specific countries data
     *
     * @return void
     */
    private function getCountriesData()
    {
        require_once __DIR__ . '/Data/' . $this->language . '.php';
        $this->countries = $world;
    }

    /**
     * Lookup ISO3166-1 data by given identifier.
     *
     * Looks for a match against the given key for each entry in the dataset.
     *
     * @param string $key
     * @param string $value
     *
     * @throws \Donsi\ISO3166\Exception\OutOfBoundsException if key does not exist in dataset
     *
     * @return array
     */
    private function lookup($key, $value)
    {
        foreach ($this->countries as $country) {
            if ($key == self::KEY_NUMERIC) {
                $country[self::KEY_NUMERIC] = sprintf('%03d', $country['id']);
            }
            if (0 === strcasecmp($value, $country[$key])) {
                $country[self::KEY_NUMERIC] = sprintf('%03d', $country['id']);
                $country['alpha2'] = strtoupper($country['alpha2']);
                $country['alpha3'] = strtoupper($country['alpha3']);
                return $country;
            }
        }

        throw new OutOfBoundsException(
            sprintf('No "%s" key found matching: %s', $key, $value)
        );
    }

    /**
     * Default dataset.
     *
     * @var array[]
     */
    private $countries = [];
}
