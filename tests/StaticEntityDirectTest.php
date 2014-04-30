<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\StaticEntity;
use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidData;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet;
use Byscripts\StaticEntity\Tests\Fixtures\MissingProperty;

class StaticEntityDirectTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $civility = Civility::get('mr');

        $this->assertInstanceOf('Byscripts\StaticEntity\Tests\Fixtures\Civility', $civility);
        $this->assertEquals('Mister', $civility->getName());
        $this->assertEquals('mr', $civility->getId());
        $this->assertEquals('Mr', $civility->getShortName());
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

    public function testAlternativeSameInstance()
    {
        $civility1 = StaticEntity::get('mr', 'Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $civility2 = Civility::get('mr');

        $this->assertSame($civility1, $civility2);
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
     * @expectedExceptionMessage must be null
     */
    public function testClassSet()
    {
        Civility::get('mr', 'Some\Class\Defined');
    }

    public function testExists()
    {
        $this->assertTrue(
            Civility::exists('mr')
        );

        $this->assertFalse(
            Civility::exists('non-existent-id')
        );
    }

    public function testToId()
    {
        $civility = Civility::get('mr');

        $this->assertEquals('mr', Civility::toId($civility));
        $this->assertEquals('mr', Civility::toId('mr'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid parameter
     */
    public function testBadToId()
    {
        Civility::toId('non-existent-id');
    }

    public function testGetAll()
    {
        $all = Civility::getAll();

        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all[0]);
        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all[1]);

        $this->assertEquals('mr', $all[0]->getId());
        $this->assertEquals('mrs', $all[1]->getId());
    }

    public function testGetAssoc()
    {
        $assoc = Civility::getAssoc();

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mister', $assoc['mr']);
        $this->assertEquals('Misses', $assoc['mrs']);
    }

    public function testGetAssocWithParam()
    {
        $assoc = Civility::getAssoc('shortName');

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mr', $assoc['mr']);
        $this->assertEquals('Mrs', $assoc['mrs']);
    }

    public function testIs()
    {
        $civility = Civility::get('mr');

        $this->assertTrue($civility->is('mr'));
        $this->assertFalse($civility->is('mrs'));
    }
}
