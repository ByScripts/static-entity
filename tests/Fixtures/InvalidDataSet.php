<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\StaticEntity;

class InvalidDataSet extends StaticEntity
{
    static function getDataSet()
    {
        return "foobar";
    }
}