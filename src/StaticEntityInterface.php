<?php
/*
 * This file is part of the ByscriptsStaticEntity package.
 *
 * (c) Thierry Goettelmann <thierry@byscripts.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Byscripts\StaticEntity;

/**
 * Interface StaticEntityInterface
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
interface StaticEntityInterface
{
    /**
     * Returns an array of arrays, indexed by the id of the entities
     *
     * Example:
     * return array(
     *      'm' => array('name' => 'Male',   'shortName' => 'M', 'loves' => 'Beer'),
     *      'f' => array('name' => 'Female', 'shortName' => 'F', 'loves' => 'Shopping')
     * );
     *
     * @return array[]
     */
    static public function getDataSet();
}
