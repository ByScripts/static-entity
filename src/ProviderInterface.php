<?php
declare(strict_types=1);

namespace Byscripts\StaticEntity;

interface ProviderInterface
{
    static function get(string $className, $id);

    static function getAll(string $className): array;

    static function hasId(string $className, $id): bool;

    static function toId(string $className, $classOrId);

    static function getAssociative(string $className, string $key = 'name'): array;

    static function getIds(string $className): array;
}
