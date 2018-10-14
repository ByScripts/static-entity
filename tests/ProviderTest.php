<?php

namespace Byscripts\StaticEntity\Tests;

use Byscripts\StaticEntity\Provider;
use Byscripts\StaticEntity\Tests\Fixtures\Civility;
use Byscripts\StaticEntity\Tests\Fixtures\WebBrowser;
use PHPUnit\Framework\TestCase;

/**
 * Class ProviderTest
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class ProviderTest extends TestCase
{
    public function testFind()
    {
        /** @var Civility $civility */
        $civility = Provider::get(Civility::class, Civility::MR);

        /** @var WebBrowser $webBrowser */
        $webBrowser = Provider::get(WebBrowser::class, WebBrowser::CHROME);

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
        Provider::get(WebBrowser::class, 'bad-id');
    }

    public function testFindReturnsSameInstance()
    {
        $civility1 = Provider::get(Civility::class, Civility::MR);
        $civility2 = Provider::get(Civility::class, Civility::MR);

        $this->assertSame($civility1, $civility2);
    }

    public function testExists()
    {
        $this->assertTrue(
            Provider::hasId(WebBrowser::class, WebBrowser::CHROME)
        );

        $this->assertFalse(
            Provider::hasId(WebBrowser::class, 'bad-id')
        );
    }

    public function testToId()
    {
        $civility = Provider::get(Civility::class, Civility::MR);

        $this->assertEquals(Civility::MR, Provider::toId(Civility::class, $civility));
        $this->assertEquals(Civility::MR, Provider::toId(Civility::class, Civility::MR));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to find ID
     */
    public function testBadIdToId()
    {
        Provider::toId(Civility::class, 'bad-id');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage does not implement
     */
    public function testBadClassToId()
    {
        Provider::toId(Civility::class, new class {});
    }

    public function testFindAll()
    {
        /** @var $all Civility[] */
        $all = Provider::getAll(Civility::class);

        $this->assertArrayHasKey(Civility::MR, $all);
        $this->assertArrayHasKey(Civility::MRS, $all);

        $this->assertInstanceOf(Civility::class, $all[Civility::MR]);
        $this->assertInstanceOf(Civility::class, $all[Civility::MRS]);

        $this->assertEquals('Male', $all[Civility::MR]->getGender());
        $this->assertEquals('Female', $all[Civility::MRS]->getGender());
    }

    public function testGetAllTwice()
    {
        $all1 = Provider::getAll(Civility::class);
        $all2 = Provider::getAll(Civility::class);

        $this->assertSame($all1, $all2);
    }

    public function testFindAssociative()
    {
        $associative = Provider::getAssociative(Civility::class, 'gender');

        $this->assertArrayHasKey(Civility::MR, $associative);
        $this->assertArrayHasKey(Civility::MRS, $associative);

        $this->assertEquals('Male', $associative[Civility::MR]);
        $this->assertEquals('Female', $associative[Civility::MRS]);

        $associative = Provider::getAssociative(Civility::class, 'shortName');

        $this->assertArrayHasKey(Civility::MR, $associative);
        $this->assertArrayHasKey(Civility::MRS, $associative);

        $this->assertEquals('Mr.', $associative[Civility::MR]);
        $this->assertEquals('Mrs', $associative[Civility::MRS]);
    }

    public function testFindIds()
    {
        $ids = Provider::getIds(Civility::class);

        $this->assertEquals([Civility::MR, Civility::MRS], $ids);
    }
}
