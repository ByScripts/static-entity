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
     * @var \ReflectionClass[]
     */
    static private $reflections = array();

    /**
     * @var array[]
     */
    static private $dataSets = array();

    /**
     * @var array[]
     */
    static private $instances = array();

    static private $allLoaded = false;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * Check the existence of the ID
     *
     * @param mixed $id The id to be tested
     *
     * @throws \Exception
     *
     * @return bool Whether the ID exists or not
     */
    static public function exists($id)
    {
        $class = self::ensureClass(__METHOD__);

        self::initDataSet();

        return array_key_exists($id, self::$dataSets[$class]);
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
        $class = self::ensureClass(__METHOD__);

        if (self::$allLoaded[$class]) {
            return self::$instances[$class];
        }

        foreach (self::getIds() as $id) {
            self::get($id);
        }

        self::$allLoaded[$class] = true;

        return self::$instances[$class];
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
    static public function getAssoc($valueKey = 'name')
    {
        $class = self::ensureClass(__METHOD__);

        self::initDataSet();

        if (empty($valueKey)) {
            $valueKey = 'name';
        }

        return array_map(
            function ($arr) use ($valueKey) {
                return $arr[$valueKey];
            },
            self::$dataSets[$class]
        );
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
        $class = self::ensureClass(__METHOD__);

        self::init();

        return array_keys(self::$dataSets[$class]);
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
        if ($idOrEntity instanceof StaticEntity) {
            return $idOrEntity->getId();
        } elseif (self::exists($idOrEntity)) {
            return $idOrEntity;
        }

        throw new \Exception(sprintf('%s => Invalid parameter: %s', __METHOD__, $idOrEntity));
    }

    /**
     * @param mixed $id
     *
     * @throws \Exception
     *
     * @return null|static
     */
    static public function get($id)
    {
        $class = self::ensureClass(__METHOD__);

        self::init();

        if (array_key_exists($id, self::$instances[$class])) {
            return self::$instances[$class][$id];
        } elseif (!array_key_exists($id, self::$dataSets[$class])) {
            return self::$instances[$class][$id] = null;
        }

        $instance = new $class;

        foreach (self::$dataSets[$class][$id] as $propertyName => $propertyValue) {
            $property = self::$reflections[$class]->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($instance, $propertyValue);
        }

        return self::$instances[$class][$id] = $instance;
    }

    static private function init()
    {
        $class = get_called_class();

        if (array_key_exists($class, self::$instances)) {
            return;
        }

        self::initDataSet();

        self::$instances[$class]   = array();
        self::$reflections[$class] = new \ReflectionClass($class);
    }

    private static function initDataSet()
    {
        $class = get_called_class();

        if (array_key_exists($class, self::$dataSets)) {
            return;
        }

        $dataSet = static::getDataSet();

        if (!is_array($dataSet)) {
            throw new \Exception(sprintf('%s::getDataSet() must returns an array', $class));
        }

        foreach ($dataSet as $id => $data) {
            self::checkData($id, $data);
            $dataSet[$id]['id'] = $id;
        }

        self::$dataSets[$class] = $dataSet;
    }

    private static function checkData($id, $data)
    {
        $class = get_called_class();

        if (!is_array($data)) {
            throw new \Exception(
                sprintf('%s::getDataSet() => Data at index "%s" must be an array', $class, $id)
            );
        }

        foreach (array_keys($data) as $property) {
            if (!property_exists($class, $property)) {
                throw new \Exception(
                    sprintf('Property "%s" not exists in class "%s"', $property, $class)
                );
            }
        }
    }

    private static function ensureClass($method)
    {
        $calledClass = get_called_class();

        if (__CLASS__ === $calledClass) {
            throw new \Exception(sprintf('You cannot call %s() directly', $method));
        }

        return $calledClass;
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
