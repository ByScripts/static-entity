<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\StaticEntity;
use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidData;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet;
use Byscripts\StaticEntity\Tests\Fixtures\MissingProperty;

/**
 * Class StaticEntityTest
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class StaticEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        /** @var $civility Civility */
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

    public function testNotFoundForceInstance()
    {
        $civility = Civility::get('not-exists', true);

        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $civility);
        $this->assertNull($civility->getId());
    }

    public function testSameInstances()
    {
        $civility1 = Civility::get('mr');
        $civility2 = Civility::get('mr');

        $this->assertSame($civility1, $civility2);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage seems invalid
     */
    public function testInvalidDataSet()
    {
        InvalidDataSet::get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage seems invalid
     */
    public function testInvalidData()
    {
        InvalidData::get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage not-exists does not exist
     */
    public function testMissingProperty()
    {
        MissingProperty::get('foo');
    }

    public function testExists()
    {
        $this->assertTrue(
            Civility::hasId('mr')
        );

        $this->assertFalse(
            Civility::hasId('non-existent-id')
        );
    }

    public function testToId()
    {
        $civility = Civility::get('mr');

        $this->assertEquals('mr', Civility::toId($civility));
        $this->assertEquals('mr', Civility::toId('mr'));
    }

    public function testEmptyToId()
    {
        $this->assertNull(Civility::toId(""));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to convert
     */
    public function testBadToId()
    {
        Civility::toId('non-existent-id');
    }

    public function testGetAll()
    {
        /** @var $all Civility[] */
        $all = Civility::getAll();

        $this->assertArrayHasKey('mr', $all);
        $this->assertArrayHasKey('mrs', $all);

        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all['mr']);
        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all['mrs']);

        $this->assertEquals('Mister', $all['mr']->getName());
        $this->assertEquals('Misses', $all['mrs']->getName());
    }

    public function testGetAllTwice()
    {
        $all1 = Civility::getAll();
        $all2 = Civility::getAll();

        $this->assertSame($all1, $all2);
    }

    public function testGetAllAfterInexistent()
    {
        Civility::get('not-exists');
        $all = Civility::getAll();

        $this->assertArrayNotHasKey('not-exists', $all);
    }

    public function testGetAssoc()
    {
        $assoc = Civility::getAssociative();

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mister', $assoc['mr']);
        $this->assertEquals('Misses', $assoc['mrs']);
    }

    public function testGetAssocWithEmptyParam()
    {
        $assoc = Civility::getAssociative(null);

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mister', $assoc['mr']);
        $this->assertEquals('Misses', $assoc['mrs']);
    }

    public function testGetAssocWithParam()
    {
        $assoc = Civility::getAssociative('shortName');

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mr', $assoc['mr']);
        $this->assertEquals('Mrs', $assoc['mrs']);
    }

    public function testIs()
    {
        /** @var $civility Civility */
        $civility = Civility::get('mr');

        $this->assertTrue($civility->is('mr'));
        $this->assertFalse($civility->is('mrs'));
    }

    public function testGetIds()
    {
        $ids = Civility::getIds();

        $this->assertEquals(array('mr', 'mrs'), $ids);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call
     */
    public function testGetOnStaticEntityClass()
    {
        StaticEntity::get(1);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call
     */
    public function testExistsOnStaticEntityClass()
    {
        StaticEntity::hasId(1);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call
     */
    public function testGetAllOnStaticEntityClass()
    {
        StaticEntity::getAll();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call
     */
    public function testGetAssocOnStaticEntityClass()
    {
        StaticEntity::getAssociative();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call
     */
    public function testGetIdsOnStaticEntityClass()
    {
        StaticEntity::getIds();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call
     */
    public function testToIdOnStaticEntityClass()
    {
        StaticEntity::toId(1);
    }
}
