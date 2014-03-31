<?php

namespace Byscripts\StaticEntity;

abstract class StaticEntity implements StaticEntityInterface
{
    static private $classes = array();

    protected $id;

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
            $dataSet[$id]['id'] = $id;

            foreach ($dataSet[$id] as $propertyName => $value) {
                if (!$reflection->hasProperty($propertyName)) {
                    throw new \Exception(sprintf('Property "%s" not exists', $propertyName));
                }
            }
        }

        self::$classes[$class]['reflection'] = $reflection;
        self::$classes[$class]['dataSet'] = $dataSet;
    }

    static private function initClass($class)
    {
        if (array_key_exists($class, self::$classes)) {
            return;
        }

        if(!is_subclass_of($class, 'Byscripts\StaticEntity\StaticEntity')) {
            throw new \Exception('Class must extends StaticEntity');
        }

        self::$classes[$class] = array(
            'instances' => array(),
            'dataSet' => null,
            'reflection' => null
        );

        self::initDataSet($class);
    }

    static private function getInstance($id, $class)
    {
        self::initClass($class);

        if(array_key_exists($id, self::$classes[$class]['instances'])) {
            return self::$classes[$class]['instances'][$id];
        }

        if (!array_key_exists($id, self::$classes[$class]['dataSet'])) {
            return self::$classes[$class]['instances'][$id] = null;
        }

        $instance = new $class;

        foreach(self::$classes[$class]['dataSet'][$id] as $propertyName => $propertyValue) {
            $property = self::$classes[$class]['reflection']->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($instance, $propertyValue);
        }

        return self::$classes[$class]['instances'][$id] = $instance;
    }

    /**
     * @param mixed       $id
     * @param string|null $class
     *
     * @return static
     * @throws \Exception
     */
    static public function get($id, $class = null)
    {
        if(get_called_class() === get_class()) {
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

    public function getId()
    {
        return $this->id;
    }

    public function is($id)
    {
        return $id === $this->getId();
    }
}
