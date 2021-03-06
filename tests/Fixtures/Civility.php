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
 * Class Civility
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
class Civility extends AbstractStaticEntity
{
    const MR = 1;
    const MRS = 2;

    private $gender;
    private $shortName;

    static function getDataSet(): array
    {
        return [
            self::MR => [
                'gender' => 'Male',
                'shortName' => 'Mr.',
            ],
            self::MRS => [
                'gender' => 'Female',
                'shortName' => 'Mrs',
            ],
        ];
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }
}
