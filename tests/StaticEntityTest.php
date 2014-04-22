<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\StaticEntity;
use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidData;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet;
use Byscripts\StaticEntity\Tests\Fixtures\MissingProperty;

class StaticEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $civility = Civility::get('mr');

        $this->assertInstanceOf('Byscripts\StaticEntity\Tests\Fixtures\Civility', $civility);
        $this->assertEquals('Monsieur', $civility->getName());
        $this->assertEquals('mr', $civility->getId());
        $this->assertEquals('M.', $civility->getShortName());
    }

    public function testNotFound()
    {
        $civility = Civility::get('not-exists');

        $this->assertNull($civility);
    }

    public function testSameInstances()
    {
        $civility1 = Civility::get('mr');
        $civility2 = Civility::get('mr');

        $this->assertSame($civility1, $civility2);
    }

    public function testAlternativeGet()
    {
        $civility = StaticEntity::get('mr', 'Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertInstanceOf('Byscripts\StaticEntity\Tests\Fixtures\Civility', $civility);
    }

    public function testAlternativeSameInstance()
    {
        $civility1 = StaticEntity::get('mr', 'Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $civility2 = Civility::get('mr');

        $this->assertSame($civility1, $civility2);

    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage not exists
     */
    public function testNotExists()
    {
        StaticEntity::get('mr', 'Byscripts\StaticEntity\Tests\Fixtures\NotExists');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage must extends StaticEntity
     */
    public function testNotExtends()
    {
        StaticEntity::get('foo', 'Byscripts\StaticEntity\Tests\Fixtures\NotExtends');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage must be an array
     */
    public function testInvalidDataSet()
    {
        InvalidDataSet::get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Data at index
     */
    public function testInvalidData()
    {
        InvalidData::get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage not exists
     */
    public function testMissingProperty()
    {
        MissingProperty::get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage $class cannot be null
     */
    public function testNoClass()
    {
        StaticEntity::get('foo');
    }
}
