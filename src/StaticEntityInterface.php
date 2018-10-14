<?php
declare(strict_types=1);

namespace Byscripts\StaticEntity;

interface StaticEntityInterface
{
    static function getDataSet(): array;

    public function getId();
}

