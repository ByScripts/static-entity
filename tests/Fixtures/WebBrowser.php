<?php
/*
 * This file is part of the ByscriptsStaticEntity package.
 *
 * (c) Thierry Goettelmann <thierry@byscripts.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Byscripts\StaticEntity\Tests\Fixtures;

use Byscripts\StaticEntity\AbstractStaticEntity;

/**
 * Class WebBrowser
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class WebBrowser extends AbstractStaticEntity
{
    const FIREFOX = 'mf';
    const CHROME = 'gc';

    private $name;
    private $vendor;

    static function getDataSet(): array
    {
        return [
            self::FIREFOX => [
                'name' => 'Firefox',
                'vendor' => 'Mozilla',
            ],
            self::CHROME => [
                'name' => 'Chrome',
                'vendor' => 'Google',
            ],
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVendor(): string
    {
        return $this->vendor;
    }
}
