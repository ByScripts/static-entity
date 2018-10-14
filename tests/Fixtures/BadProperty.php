<?php

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\AbstractStaticEntity;

class BadProperty extends AbstractStaticEntity
{
    static function getDataSet(): array
    {
        return [
            1 => [
                'foo' => 'bar',
            ],
        ];
    }
}
