<?php
/*
 * This file is part of the ByscriptsStaticEntity package.
 *
 * (c) Thierry Goettelmann <thierry@byscripts.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Byscripts\StaticEntity;

/**
 * Class StaticEntity
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
abstract class StaticEntity implements StaticEntityInterface
{
    /**
     * @var  StaticEntityBuilder
     */
    private static $builders;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * Get a static entity instance
     *
     * @param mixed $identifier
     *
     * @return mixed
     */
    static public function get($identifier)
    {
        return self::getBuilder()->get($identifier);
    }

    /**
     * Check the existence of the ID
     *
     * @param mixed $id The id to be tested
     *
     * @throws \Exception
     *
     * @return bool Whether the ID exists or not
     */
    static public function hasId($id)
    {
        return self::getBuilder()->hasId($id);
    }

    /**
     * Returns an array of all instances
     *
     * @throws \Exception
     *
     * @return array
     */
    static public function getAll()
    {
        return self::getBuilder()->getAll();
    }

    /**
     * Returns an associative array indexed by ID
     *
     * @param string $valueKey The key to use to hydrate the values
     *
     * @throws \Exception
     *
     * @return array
     */
    static public function getAssociative($valueKey = 'name')
    {
        return self::getBuilder()->getAssociative($valueKey);
    }

    /**
     * Returns an array of all IDs
     *
     * @throws \Exception
     *
     * @return array
     */
    static public function getIds()
    {
        return self::getBuilder()->getIds();
    }

    /**
     * If the parameter is a static entity, returns its id.
     * Else check if the parameter is an existent ID and returns it.
     *
     * @param mixed $idOrEntity
     *
     * @throws \Exception
     *
     * @return mixed
     */
    static public function toId($idOrEntity)
    {
        return self::getBuilder()->convertToId($idOrEntity);
    }


    /**
     * Get the StaticEntity builder
     *
     * @throws \Exception
     * @return StaticEntityBuilder
     */
    private static function getBuilder()
    {
        $class = get_called_class();

        if (__CLASS__ === $class) {
            throw new \Exception('You cannot call methods directly on StaticEntity class');
        }

        if (!isset(self::$builders[$class])) {
            self::$builders[$class] = new StaticEntityBuilder($class);
        }

        return self::$builders[$class];
    }

    /**
     * Returns the ID of the current instance
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Check if the passed ID is the ID of the current instance
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function is($id)
    {
        return $id === $this->getId();
    }
}
