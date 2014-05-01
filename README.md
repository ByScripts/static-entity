# Byscripts Static Entity

Provides an easy way to get some entity/model behavior with static data

## Installation

### Add the package in your composer.json

```json
{
    "require": {
        "byscripts/static-entity": "~1.0"
    }
}
```

Then run `composer update` (or `composer update byscripts/static-entity` if you don't want to update all your packages)

### Usage

#### Create your static entity

```php
use Byscripts\StaticEntity\StaticEntity;

class WebBrowser extends StaticEntity
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
    
    static public function getDataSet()
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
// Get an instance of WebBrowser, hydrated with Firefox data
$firefox = WebBrowser::get(WebBrowser::FIREFOX);

// Instanciated objects are singleton
WebBrowser::get(WebBrowser::FIREFOX) === WebBrowser::get(WebBrowser::FIREFOX); // true

// The getId() method is always available.
// It returns the key used in the getDataSet() method;
$firefox->getId(); // 2

// Other methods are ones implemented in the static entity
$firefox->getName(); // Firefox

// The is() method check the argument against the instance ID
$firefox->is(WebBrowser::CHROMIUM); // false
$firefox->is(WebBrowser::FIREFOX);  // true

// The toId() method transform an entity to ID.
// If an id is passed, it is returned as is, after checking it exists.
// The method is mainly intended for a setter method to accept both type.
WebBrowser::toId($firefox); // 2
WebBrowser::toId(2);        // 2

// The getIds() returns an array of ... well, ids.
WebBrowser::getIds(); // [1, 2, 3, 4, 5]

// The getAssoc() returns an associative array with `id` as key and `name` as value
WebBrowser::getAssoc(); // [1 => 'Chromium', 2 => 'Firefox', ...]

// You can also pass the name of an argument you want to use as value
WebBrowser::getAssoc('brand'); // [1 => 'Google', 2 => 'Mozilla', 3 => 'Microsoft', ...]

// The getAll() method return an array containing all instances of entities
WebBrowser::getAll(); // [Object, Object, ...]

// The exists() method check whether the passed ID exists in data set
WebBrowser::exists(3); // true
WebBrowser::exists(9); // false
```

#### Alternative usage

All static methods can be called indirectly from StaticEntity class by passing the desired class as last method argument.

```
StaticEntity::get(2, 'WebBrowser');
StaticEntity::getAssoc('brand', 'WebBrowser');
```
