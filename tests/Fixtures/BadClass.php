<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

class BadClass
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
