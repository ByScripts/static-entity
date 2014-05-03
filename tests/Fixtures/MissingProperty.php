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
 * Class MissingProperty
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class MissingProperty extends StaticEntity
{
    /**
     * @return array|\array[]
     */
    static public function getDataSet()
    {
        return array(
            'foo' => array('not-exists' => 'foobar')
        );
    }
}
