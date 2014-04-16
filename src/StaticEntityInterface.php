<?php


namespace Byscripts\StaticEntity;

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
    static function getDataSet();
}