<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\StaticEntity;
use Byscripts\StaticEntity\StaticEntityManager;
use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidData;
use Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet;
use Byscripts\StaticEntity\Tests\Fixtures\MissingProperty;

/**
 * Class StaticEntityTest
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class StaticEntityBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        /** @var $civility Civility */
        $civility = $builder->get('mr');

        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $civility);
        $this->assertEquals('Mister', $civility->getName());
        $this->assertEquals('mr', $civility->getId());
        $this->assertEquals('Mr', $civility->getShortName());
    }

    public function testNotFound()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $civility = $builder->get('not-exists');

        $this->assertNull($civility);
    }

    public function testSameInstances()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $civility1 = $builder->get('mr');
        $civility2 = $builder->get('mr');

        $this->assertSame($civility1, $civility2);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage seems invalid
     */
    public function testInvalidDataSet()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\InvalidDataSet');
        $builder->get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage seems invalid
     */
    public function testInvalidData()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\InvalidData');
        $builder->get('foo');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage not-exists does not exist
     */
    public function testMissingProperty()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\MissingProperty');
        $builder->get('foo');
    }

    public function testExists()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $this->assertTrue(
            $builder->hasId('mr')
        );

        $this->assertFalse(
            $builder->hasId('non-existent-id')
        );
    }

    public function testToId()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $civility = $builder->get('mr');

        $this->assertEquals('mr', $builder->convertToId($civility));
        $this->assertEquals('mr', $builder->convertToId('mr'));
    }

    public function testEmptyToId()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $this->assertNull($builder->convertToId(""));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to convert
     */
    public function testBadToId()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $builder->convertToId('non-existent-id');
    }

    public function testGetAll()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        /** @var $all Civility[] */
        $all = $builder->getAll();

        $this->assertArrayHasKey('mr', $all);
        $this->assertArrayHasKey('mrs', $all);

        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all['mr']);
        $this->assertInstanceOf('\Byscripts\StaticEntity\Tests\Fixtures\Civility', $all['mrs']);

        $this->assertEquals('Mister', $all['mr']->getName());
        $this->assertEquals('Misses', $all['mrs']->getName());
    }

    public function testGetAllTwice()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        $all1 = $builder->getAll();
        $all2 = $builder->getAll();

        $this->assertSame($all1, $all2);
    }

    public function testGetAssoc()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $assoc = $builder->getAssociative('name');

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mister', $assoc['mr']);
        $this->assertEquals('Misses', $assoc['mrs']);

        $assoc = $builder->getAssociative('shortName');

        $this->assertArrayHasKey('mr', $assoc);
        $this->assertArrayHasKey('mrs', $assoc);

        $this->assertEquals('Mr', $assoc['mr']);
        $this->assertEquals('Mrs', $assoc['mrs']);
    }

    public function testIs()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');

        /** @var $civility Civility */
        $civility = $builder->get('mr');

        $this->assertTrue($civility->is('mr'));
        $this->assertFalse($civility->is('mrs'));
    }

    public function testGetIds()
    {
        $builder = new StaticEntityManager('\Byscripts\StaticEntity\Tests\Fixtures\Civility');
        $ids = $builder->getIds();

        $this->assertEquals(array('mr', 'mrs'), $ids);
    }
}
