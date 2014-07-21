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
 * Class StaticEntityBuilder
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class StaticEntityManager
{
    private $class;

    /**
     * @var StaticEntity[]
     */
    private $instances = array();

    /**
     * @var array
     */
    private $ids = array();

    /**
     * @var array
     */
    private $dataSet;

    /**
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * @param string $staticEntityClass
     */
    public function __construct($staticEntityClass)
    {
        $this->class      = $staticEntityClass;
        $this->reflection = new \ReflectionClass($this->class);
        $this->initDataSet();
    }

    /**
     * @param mixed   $id
     * @param boolean $forceInstance Whether returns an empty instance when no result
     *
     * @return StaticEntity|null
     */
    public function get($id, $forceInstance = false)
    {
        if (array_key_exists($id, $this->instances)) {
            return null === $this->instances[$id] ? $this->reflection->newInstance() : $this->instances[$id];
        } elseif (!$data = $this->getData($id)) {
            $this->instances[$id] = null;
            return $forceInstance ? $this->reflection->newInstance() : null;
        }

        $this->instances[$id] = $this->reflection->newInstance();

        foreach ($data as $property => $value) {
            $reflectionProperty = $this->reflection->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($this->instances[$id], $value);
        }

        return $this->instances[$id];
    }

    /**
     * @param string $valueKey
     *
     * @return array
     * @throws \Exception
     */
    public function getAssociative($valueKey = 'name')
    {
        if (empty($valueKey)) {
            $valueKey = 'name';
        }

        return array_map(
            function ($arr) use ($valueKey) {
                return $arr[$valueKey];
            },
            $this->dataSet
        );
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        foreach ($this->ids as $id) {
            $this->get($id);
        }

        return array_filter($this->instances);
    }

    /**
     * @param mixed $id
     *
     * @return bool
     */
    public function hasId($id)
    {
        return in_array($id, $this->ids, true);
    }

    /**
     * @param mixed|StaticEntity $staticEntity
     *
     * @return mixed
     * @throws \Exception
     */
    public function convertToId($staticEntity)
    {
        if (empty($staticEntity) && '0' !== $staticEntity && 0 !== $staticEntity) {
            return null;
        } elseif ($staticEntity instanceof StaticEntity) {
            return $staticEntity->getId();
        } elseif (!$this->hasId($staticEntity)) {
            throw new \Exception(
                sprintf('Unable to convert "%s" to a valid id for class %s', $staticEntity, $this->class)
            );
        }

        return $staticEntity;
    }

    /**
     * @throws \Exception
     *
     * @return array
     */
    private function initDataSet()
    {
        $dataSet = call_user_func(array($this->class, 'getDataSet'));

        if (!is_array($dataSet) || count($dataSet) !== count(array_filter($dataSet, 'is_array'))) {
            throw new \Exception('DataSet for class %s seems invalid');
        }

        $this->ids = array_keys($dataSet);

        foreach ($this->ids as $id) {
            $dataSet[$id]['id'] = $id;
        }

        $this->dataSet = $dataSet;
    }

    private function getData($id)
    {
        return array_key_exists($id, $this->dataSet)
            ? $this->dataSet[$id]
            : null;
    }
}
