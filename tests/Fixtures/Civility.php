<?php
/*
 * This file is part of the ByscriptsStaticEntity package.
 *
 * (c) Thierry Goettelmann <thierry@byscripts.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\StaticEntity;

/**
 * Class Civility
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class Civility extends StaticEntity
{
    private $name;
    private $shortName;

    /**
     * @return array[]
     */
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
