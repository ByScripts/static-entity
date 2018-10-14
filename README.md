# Byscripts Static Entity

Provides an easy way to get some entity/model behavior with static data

[![Build Status](https://travis-ci.org/ByScripts/static-entity.svg?branch=v3.0)](https://travis-ci.org/ByScripts/static-entity)
[![Latest Stable Version](https://poser.pugx.org/byscripts/static-entity/v/stable.png)](https://packagist.org/packages/byscripts/static-entity) 
[![License](https://poser.pugx.org/byscripts/static-entity/license.png)](https://packagist.org/packages/byscripts/static-entity)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ByScripts/static-entity/badges/quality-score.png?b=v3.0)](https://scrutinizer-ci.com/g/ByScripts/static-entity/?branch=v3.0)
[![Code Coverage](https://scrutinizer-ci.com/g/ByScripts/static-entity/badges/coverage.png?b=v3.0)](https://scrutinizer-ci.com/g/ByScripts/static-entity/?branch=v3.0)
[![Codeship Status for ByScripts/ByscriptsStaticEntity](https://codeship.com/projects/9ec69660-346a-0133-a68d-56c8db4126b8/status?branch=v3.0)](https://codeship.com/projects/100498)

## Installation

### Add the package in your composer.json

At command line, run `composer require byscripts/static-entity:~3.0`

### Usage

#### Create your static entity

```php
<?php
use Byscripts\StaticEntity\AbstractStaticEntity;

class WebBrowser extends AbstractStaticEntity
{
    const CHROMIUM = 1;
    const FIREFOX  = 2;
    const IE       = 3;
    const OPERA    = 4;
    const SAFARI   = 5;

    private $name;
    private $brand;
    private $engine;
    private $license;

    public function getName()
    {
        return $this->name;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function getLicense()
    {
        return $this->license;
    }

    static public function getDataSet(): array
    {
        return [
            self::CHROMIUM => [
                'name'    => 'Chromium',
                'brand'   => 'Google',
                'engine'  => 'Blink',
                'license' => 'BSD'
            ],
            self::FIREFOX => [
                'name'    => 'Firefox',
                'brand'   => 'Mozilla',
                'engine'  => 'Gecko',
                'license' => 'MPL'
            ],
            self::IE => [
                'name'    => 'Internet Explorer',
                'brand'   => 'Microsoft',
                'engine'  => 'Trident',
                'license' => 'Proprietary'
            ],
            self::OPERA => [
                'name'    => 'Opera',
                'brand'   => 'Opera Software',
                'engine'  => 'Blink',
                'license' => 'Proprietary'
            ],
            self::SAFARI => [
                'name'    => 'Safari',
                'brand'   => 'Apple',
                'engine'  => 'WebKit',
                'license' => 'Proprietary'
            ]
        ];
    }
}
```

#### Play with it

```php
<?php

// Get an instance of WebBrowser, hydrated with Firefox data
$firefox = WebBrowser::get(WebBrowser::FIREFOX);

// Instanciated objects are singleton
WebBrowser::get(WebBrowser::FIREFOX) === WebBrowser::get(WebBrowser::FIREFOX); // true

// The getId() method is always available.
// It returns the key used in the getDataSet() method;
$firefox->getId(); // 2

// Other methods are ones implemented in the static entity
$firefox->getName(); // Firefox

// The toId() method transform an entity to ID.
// If an id is passed, it is returned as is, after checking it exists.
// The method is mainly intended for a setter method to accept both type.
WebBrowser::toId($firefox); // 2
WebBrowser::toId(2);        // 2

// The getIds() method returns an array of all ids present in data set
WebBrowser::getIds(); // [1, 2, 3, 4, 5]

// The getAssoc() returns an associative array with `id` as key and `name` as value
WebBrowser::getAssociative(); // [1 => 'Chromium', 2 => 'Firefox', ...]

// You can also pass the name of an argument you want to use as value
WebBrowser::getAssociative('brand'); // [1 => 'Google', 2 => 'Mozilla', 3 => 'Microsoft', ...]

// The getAll() method returns an array containing all instances of entities
WebBrowser::getAll(); // [Object, Object, ...]

// The exists() method check whether the passed ID exists in data set
WebBrowser::hasId(3); // true
WebBrowser::hasId(9); // false
```

#### Alternative usage

You can also use the `Provider` class.

In this case, your entity is not required to extends `AbstractStaticEntity`,
but still needs to implements `StaticEntityInterface`

```php
<?php
use Byscripts\StaticEntity\Provider;

Provider::get(WebBrowser::class, WebBrowser::FIREFOX);
Provider::getAssociative(WebBrowser::class);
Provider::getAssociative(WebBrowser::class, 'otherKey');
Provider::getIds(WebBrowser::class);
Provider::getAll(WebBrowser::class);
Provider::hasId(WebBrowser::class, WebBrowser::CHROMIUM);
Provider::toId(WebBrowser::class, $instanceOrId);
```
