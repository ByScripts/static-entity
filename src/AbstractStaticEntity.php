<?php
declare(strict_types=1);

namespace Byscripts\StaticEntity;

abstract class AbstractStaticEntity implements StaticEntityInterface
{
    protected $id;

    /**
     * @param $id
     *
     * @return $this
     */
    static public function get($id): StaticEntityInterface
    {
        return Provider::get(get_called_class(), $id);
    }

    static public function getAll(): array
    {
        return Provider::getAll(get_called_class());
    }

    static public function hasId($id): bool
    {
        return Provider::hasId(get_called_class(), $id);
    }

    static public function toId($classOrId)
    {
        return Provider::toId(get_called_class(), $classOrId);
    }

    static public function getAssociative(string $key = 'name'): array
    {
        return Provider::getAssociative(get_called_class(), $key);
    }

    static public function getIds(): array
    {
        return Provider::getIds(get_called_class());
    }

    public function getId()
    {
        return $this->id;
    }
}
