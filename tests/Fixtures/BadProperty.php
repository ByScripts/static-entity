<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\AbstractStaticEntity;

class BadProperty extends AbstractStaticEntity
{
    private $foo;

    static function getDataSet(): array
    {
        return [
            1 => [
                'foo' => 'bar',
            ],
            2 => [
                'foo' => 'bar',
                'bar' => 'baz',
            ],
        ];
    }
}
