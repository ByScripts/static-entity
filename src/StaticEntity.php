<?php

namespace Byscripts\StaticEntity;

abstract class StaticEntity implements StaticEntityInterface
{
    protected $id;
    static private $classes = array();

    /**
     * Check the existence of the ID
     *
     * @param mixed $id The id to be tested
     *
     * @return bool Whether the ID exists or not
     */
    static public function exists($id)
    {
        return array_key_exists($id, self::getDataSet());
    }

    /**
     * Get an instance for the passed ID
     *
     * @param mixed       $id
     * @param string|null $class
     *
     * @return static
     * @throws \Exception
     */
    static public function get($id, $class = null)
    {
        if (get_called_class() === get_class()) {
            if (null === $class) {
                throw new \Exception('$class cannot be null');
            } else {
                if (!class_exists($class)) {
                    throw new \Exception('Class not exists');
                }
            }
        } else {
            $class = get_called_class();
        }

        return self::getInstance($id, $class);
    }

    /**
     * Returns an associative array indexed by ID
     *
     * @param string $valueKey The key to use to hydrate the values
     *
     * @return array
     */
    static function getAssoc($valueKey = 'name')
    {
        return array_map(
            function ($arr) use ($valueKey) {
                return $arr[ $valueKey ];
            },
            static::getDataSet()
        );
    }

    /**
     * Returns an array of all IDs
     *
     * @return array
     */
    static public function getIds()
    {
        return array_keys(static::getDataSet());
    }

    /**
     * @param $id
     * @param $class
     *
     * @return null|StaticEntity
     */
    static private function getInstance($id, $class)
    {
        self::initClass($class);

        if (array_key_exists($id, self::$classes[ $class ]['instances'])) {
            return self::$classes[ $class ]['instances'][ $id ];
        }

        if (!array_key_exists($id, self::$classes[ $class ]['dataSet'])) {
            return self::$classes[ $class ]['instances'][ $id ] = null;
        }

        $instance = new $class;

        foreach (self::$classes[ $class ]['dataSet'][ $id ] as $propertyName => $propertyValue) {
            $property = self::$classes[ $class ]['reflection']->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($instance, $propertyValue);
        }

        return self::$classes[ $class ]['instances'][ $id ] = $instance;
    }

    /**
     * @param $class
     *
     * @throws \Exception
     */
    static private function initClass($class)
    {
        if (array_key_exists($class, self::$classes)) {
            return;
        }

        if (!is_subclass_of($class, 'Byscripts\StaticEntity\StaticEntity')) {
            throw new \Exception('Class must extends StaticEntity');
        }

        self::$classes[ $class ] = array(
            'instances'  => array(),
            'dataSet'    => null,
            'reflection' => null
        );

        self::initDataSet($class);
    }

    /**
     * @param $class
     *
     * @throws \Exception
     */
    static private function initDataSet($class)
    {
        $dataSet = call_user_func(array($class, 'getDataSet'));

        if (!is_array($dataSet)) {
            throw new \Exception('$dataSet must be an array');
        }

        $reflection = new \ReflectionClass($class);

        foreach ($dataSet as $id => $data) {
            if (!is_array($data)) {
                throw new \Exception(sprintf('Data at index "%s" must be an array', $id));
            }
            $dataSet[ $id ]['id'] = $id;

            foreach ($dataSet[ $id ] as $propertyName => $value) {
                if (!$reflection->hasProperty($propertyName)) {
                    throw new \Exception(sprintf('Property "%s" not exists', $propertyName));
                }
            }
        }

        self::$classes[ $class ]['reflection'] = $reflection;
        self::$classes[ $class ]['dataSet']    = $dataSet;
    }

    /**
     * If the parameter is a static entity, returns its id.
     * Else check if the parameter is an existent ID and returns it.
     *
     * @param $idOrEntity
     *
     * @throws \Exception
     * @return mixed
     */
    static public function toId($idOrEntity)
    {
        if ($idOrEntity instanceof StaticEntity) {
            return $idOrEntity->getId();
        } elseif (self::exists($idOrEntity)) {
            return $idOrEntity;
        }

        throw new \Exception('StaticEntity::toId() => Invalid parameter');
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
     * @param $id
     *
     * @return bool
     */
    public function is($id)
    {
        return $id === $this->getId();
    }
}
