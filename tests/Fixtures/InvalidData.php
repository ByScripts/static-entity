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
 * Class InvalidData
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class InvalidData extends StaticEntity
{
    /**
     * @return array
     */
    static public function getDataSet()
    {
        return array(
            'foo' => 'not an array'
        );
    }
}
