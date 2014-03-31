<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\StaticEntity;

class InvalidData extends StaticEntity
{
    static function getDataSet()
    {
        return array(
            'foo' => 'not an array'
        );
    }
}