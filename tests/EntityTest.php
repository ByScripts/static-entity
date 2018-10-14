<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\WebBrowser;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityTest
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class EntityTest extends TestCase
{
    public function testFind()
    {
        $civility = Civility::get(Civility::MR);
        $webBrowser = WebBrowser::get(WebBrowser::CHROME);

        $this->assertInstanceOf(Civility::class, $civility);
        $this->assertEquals(Civility::MR, $civility->getId());
        $this->assertEquals('Male', $civility->getGender());
        $this->assertEquals('Mr.', $civility->getShortName());

        $this->assertInstanceOf(WebBrowser::class, $webBrowser);
        $this->assertEquals(WebBrowser::CHROME, $webBrowser->getId());
        $this->assertEquals('Chrome', $webBrowser->getName());
        $this->assertEquals('Google', $webBrowser->getVendor());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to find data
     */
    public function testNotFound()
    {
        WebBrowser::get('bad-id');
    }

    public function testFindReturnsSameInstance()
    {
        $civility1 = Civility::get(Civility::MR);
        $civility2 = Civility::get(Civility::MR);

        $this->assertSame($civility1, $civility2);
    }

    public function testExists()
    {
        $this->assertTrue(
            WebBrowser::hasId(WebBrowser::CHROME)
        );

        $this->assertFalse(
            WebBrowser::hasId('bad-id')
        );
    }

    public function testToId()
    {
        $civility = Civility::get(Civility::MR);

        $this->assertEquals(Civility::MR, Civility::toId($civility));
        $this->assertEquals(Civility::MR, Civility::toId(Civility::MR));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to find ID
     */
    public function testBadIdToId()
    {
        Civility::toId('bad-id');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage does not implement
     */
    public function testBadClassToId()
    {
        Civility::toId(new class {});
    }

    public function testFindAll()
    {
        /** @var $all Civility[] */
        $all = Civility::getAll();

        $this->assertArrayHasKey(Civility::MR, $all);
        $this->assertArrayHasKey(Civility::MRS, $all);

        $this->assertInstanceOf(Civility::class, $all[Civility::MR]);
        $this->assertInstanceOf(Civility::class, $all[Civility::MRS]);

        $this->assertEquals('Male', $all[Civility::MR]->getGender());
        $this->assertEquals('Female', $all[Civility::MRS]->getGender());
    }

    public function testGetAllTwice()
    {
        $all1 = Civility::getAll();
        $all2 = Civility::getAll();

        $this->assertSame($all1, $all2);
    }

    public function testFindAssociative()
    {
        $associative = Civility::getAssociative('gender');

        $this->assertArrayHasKey(Civility::MR, $associative);
        $this->assertArrayHasKey(Civility::MRS, $associative);

        $this->assertEquals('Male', $associative[Civility::MR]);
        $this->assertEquals('Female', $associative[Civility::MRS]);

        $associative = Civility::getAssociative('shortName');

        $this->assertArrayHasKey(Civility::MR, $associative);
        $this->assertArrayHasKey(Civility::MRS, $associative);

        $this->assertEquals('Mr.', $associative[Civility::MR]);
        $this->assertEquals('Mrs', $associative[Civility::MRS]);
    }

    public function testFindIds()
    {
        $ids = Civility::getIds();

        $this->assertEquals([Civility::MR, Civility::MRS], $ids);
    }
}
