<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\StaticEntity;

class StaticEntityIndirectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        /** @var $civility \Byscripts\StaticEntity\Tests\Fixtures\Civility */
        $civility = StaticEntity::get('mr', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertInstanceOf('Byscripts\StaticEntity\Tests\Fixtures\Civility', $civility);
        $this->assertEquals('Mister', $civility->getName());
        $this->assertEquals('mr', $civility->getId());
        $this->assertEquals('Mr', $civility->getShortName());
    }

    public function testNotFound()
    {
        $civility = StaticEntity::get('non-existent-id', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertNull($civility);
    }

    public function testSameInstances()
    {
        $civility1 = StaticEntity::get('mr', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $civility2 = StaticEntity::get('mr', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertSame($civility1, $civility2);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage not exists
     */
    public function testNotExists()
    {
        StaticEntity::get('mr', 'This\Class\Does\Not\Exists');
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
        StaticEntity::get('foo', '\Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Data at index
     */
    public function testInvalidData()
    {
        StaticEntity::get('foo', '\Byscripts\StaticEntity\Tests\Fixtures\InvalidData');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage not exists
     */
    public function testMissingProperty()
    {
        StaticEntity::get('foo', '\Byscripts\StaticEntity\Tests\Fixtures\MissingProperty');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage $class cannot be null
     */
    public function testNoClass()
    {
        StaticEntity::get('foo');
    }

    public function testExists()
    {
        $this->assertTrue(
            StaticEntity::exists('mr', '\Byscripts\StaticEntity\Tests\Fixtures\Civility')
        );

        $this->assertFalse(
            StaticEntity::exists('non-existent-id', '\Byscripts\StaticEntity\Tests\Fixtures\Civility')
        );
    }

    public function testToId()
    {
        /** @var $civility \Byscripts\StaticEntity\Tests\Fixtures\Civility */
        $civility = StaticEntity::get('mr', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertEquals('mr', StaticEntity::toId($civility, '\Byscripts\StaticEntity\Tests\Fixtures\Civility'));
        $this->assertEquals('mr', StaticEntity::toId('mr', '\Byscripts\StaticEntity\Tests\Fixtures\Civility'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid parameter
     */
    public function testBadToId()
    {
        StaticEntity::toId('non-existent-id', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');
    }

    public function testGetAll()
    {
        $all = StaticEntity::getAll('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all[0]);
        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all[1]);

        $this->assertEquals('mr', $all[0]->getId());
        $this->assertEquals('mrs', $all[1]->getId());
    }

    public function testGetAssoc()
    {
        $assoc = StaticEntity::getAssoc(null, '\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mister', $assoc['mr']);
        $this->assertEquals('Misses', $assoc['mrs']);
    }

    public function testGetAssocWithParam()
    {
        $assoc = StaticEntity::getAssoc('shortName', '\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mr', $assoc['mr']);
        $this->assertEquals('Mrs', $assoc['mrs']);
    }
}
