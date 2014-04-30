<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\StaticEntity;

class Civility extends StaticEntity
{
    private $name;
    private $shortName;

    static public function getDataSet()
    {
        return array(
            'mr'  => array('name' => 'Mister', 'shortName' => 'Mr'),
            'mrs' => array('name' => 'Misses', 'shortName' => 'Mrs'),
        );
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }
}
