<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use DWenzel\T3events\Domain\Model\Dto\OrderAwareDemandTrait;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class OrderAwareDemandTraitTest extends UnitTestCase
{
    /**
     * @var OrderAwareDemandTrait
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            OrderAwareDemandTrait::class
        );
    }

    /**
     * @test
     */
    public function getOrderInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getOrder()
        );
    }

    /**
     * @test
     */
    public function orderCanBeSet()
    {
        $order = 'foo|bar';
        $this->subject->setOrder($order);

        $this->assertSame(
            $order,
            $this->subject->getOrder()
        );
    }
}
