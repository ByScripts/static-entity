<?php
declare(strict_types=1);

namespace Byscripts\StaticEntity;

interface StaticEntityInterface
{
    public function getId();

    static function getDataSet(): array;
}

