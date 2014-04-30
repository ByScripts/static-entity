<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\StaticEntity;
use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidData;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet;
use Byscripts\StaticEntity\Tests\Fixtures\MissingProperty;
use Byscripts\StaticEntity\Tests\Fixtures\NotExtends;

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
}
