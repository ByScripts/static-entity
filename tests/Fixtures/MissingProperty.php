<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\StaticEntity;

class MissingProperty extends StaticEntity
{
    static function getDataSet()
    {
        return array(
            'foo' => array('not-exists' => 'foobar')
        );
    }
}