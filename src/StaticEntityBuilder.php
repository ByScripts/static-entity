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
class StaticEntityBuilder
{
    private $class;

    private $instances = array();
    private $ids       = array();
    private $dataSet;

    /**
     * @param string $staticEntityClass
     */
    public function __construct($staticEntityClass)
    {
        $this->class = $staticEntityClass;
        $this->initDataSet();
    }

    /**
     * @param mixed $id
     *
     * @return StaticEntity|null
     */
    public function get($id)
    {
        $this->createInstance($id);

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
        $this->initDataSet();

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
            $this->createInstance($id);
        }

        return $this->instances;
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
        if ($staticEntity instanceof StaticEntity) {
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
        if (null !== $this->dataSet) {
            return;
        }

        $dataSet = call_user_func(array($this->class, 'getDataSet'));

        if (!is_array($dataSet)) {
            throw new \Exception(sprintf('%s::getDataSet() must returns an array', $this->class));
        }

        foreach ($dataSet as $id => $data) {
            $this->checkData($data, $id);
            $this->ids[] = $id;
            $dataSet[$id]['id'] = $id;
        }

        $this->dataSet = $dataSet;
    }

    private function checkData($data, $id)
    {
        if (!is_array($data)) {
            throw new \Exception(sprintf('Data at index "%s" must be an array in %s', $id, $this->class));
        }
    }

    private function getData($id)
    {
        return array_key_exists($id, $this->dataSet)
            ? $this->dataSet[$id]
            : null;
    }

    /**
     * @param mixed $id
     *
     * @return StaticEntity
     */
    private function createInstance($id)
    {
        if (array_key_exists($id, $this->instances)) {
            return;
        }

        $reflectionClass = new \ReflectionClass($this->class);
        $instance        = $reflectionClass->newInstance();

        if (null === ($data = $this->getData($id))) {
            $this->instances[$id] = null;

            return;
        }

        foreach ($data as $property => $value) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($instance, $value);
        }

        $this->instances[$id] = $instance;
    }
}
