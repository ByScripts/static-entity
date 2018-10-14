<?php
declare(strict_types=1);

namespace Byscripts\StaticEntity;

class Provider implements ProviderInterface
{
    static private $instances = [];

    /**
     * @param string $className
     * @param mixed  $id
     *
     * @return StaticEntityInterface
     */
    static public function get(string $className, $id): StaticEntityInterface
    {
        if (isset(self::$instances[$className][$id])) {
            return self::$instances[$className][$id];
        }

        self::checkClass($className);

        $dataSet = self::getDataSet($className);

        if (!isset($dataSet[$id])) {
            throw new \InvalidArgumentException(
                sprintf('Unable to find data for class "%s" with ID "%s"', $className, $id)
            );
        }

        self::build($className, $id, $dataSet[$id]);

        return self::$instances[$className][$id];
    }

    /**
     * @param string $className
     *
     * @return array
     */
    static public function getAll(string $className): array
    {
        self::checkClass($className);

        $dataSet = self::getDataSet($className);

        if (isset(self::$instances[$className]) && count($dataSet) === count(self::$instances[$className])) {
            return self::$instances[$className];
        }

        $ids = array_keys($dataSet);

        foreach ($ids as $id) {
            self::build($className, $id, $dataSet[$id]);
        }

        return self::$instances[$className];
    }

    /**
     * @param string $className
     * @param mixed  $id
     *
     * @return bool
     */
    static public function hasId(string $className, $id): bool
    {
        self::checkClass($className);

        $dataSet = self::getDataSet($className);

        return isset($dataSet[$id]);
    }

    /**
     * @param string $className
     * @param mixed  $classOrId
     *
     * @return mixed
     */
    static public function toId(string $className, $classOrId)
    {
        self::checkClass($className);

        if ($classOrId instanceof StaticEntityInterface) {
            return $classOrId->getId();
        } elseif (is_object($classOrId)) {
            throw new \InvalidArgumentException(
                sprintf('Class "%s" does not implement "%s"', get_class(), StaticEntityInterface::class)
            );
        }

        if (self::hasId($className, $classOrId)) {
            return $classOrId;
        }

        throw new \InvalidArgumentException(
            sprintf('Unable to find ID "%s" in dataSet of class "%s"', $classOrId, $className)
        );
    }

    /**
     * @param string $className
     * @param string $key
     *
     * @return array
     */
    static public function getAssociative(string $className, string $key = 'name'): array
    {
        self::checkClass($className);

        $dataSet = self::getDataSet($className);

        return array_map(
            function ($data) use ($key) {
                return $data[$key];
            },
            $dataSet
        );
    }

    /**
     * @param string $className
     *
     * @return array
     */
    static public function getIds(string $className): array
    {
        self::checkClass($className);

        $dataSet = self::getDataSet($className);

        return array_keys($dataSet);
    }

    /**
     * @param string $className
     */
    static private function checkClass(string $className): void
    {
        $interfaceName = StaticEntityInterface::class;

        if (!is_subclass_of($className, $interfaceName)) {
            throw new \InvalidArgumentException("${className} must implements ${interfaceName}");
        }
    }

    /**
     * @param string $className
     *
     * @return array
     */
    static private function getDataSet(string $className): array
    {
        return call_user_func([$className, 'getDataSet']);
    }

    /**
     * @param string $className
     * @param mixed  $id
     * @param array  $data
     */
    static private function build(string $className, $id, array $data): void
    {
        if (isset(self::$instances[$className][$id])) {
            return;
        }

        $instance = new $className();

        $data['id'] = $id;
        self::hydrate($instance, $data);

        self::$instances[$className][$id] = $instance;
    }

    static private function hydrate(StaticEntityInterface $instance, array $data): void
    {
        $reflectionClass = new \ReflectionClass($instance);

        foreach ($data as $key => $value) {
            if (!$reflectionClass->hasProperty($key)) {
                throw new \InvalidArgumentException(
                    sprintf('Class "%s" has no property "%s"', $reflectionClass->name, $key)
                );
            }
            $property = $reflectionClass->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($instance, $value);
        }
    }
}
