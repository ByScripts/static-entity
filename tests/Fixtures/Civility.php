<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\StaticEntity;

class Civility extends StaticEntity
{
    private $id;
    private $name;
    private $shortName;

    static public function getDataSet()
    {
        return array(
            'mr'  => array('name' => 'Monsieur', 'shortName' => 'M.'),
            'mrs' => array('name' => 'Madame', 'shortName' => 'Mme'),
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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