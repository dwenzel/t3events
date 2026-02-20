<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

/***************************************************************
     *  Copyright notice
     *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
     *            Michael Kasten <kasten@webfox01.de>, Agentur Webfox
     *  All rights reserved
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 2 of the License, or
     *  (at your option) any later version.
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Dto\AbstractDemand.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 * @coversDefaultClass \DWenzel\T3events\Domain\Model\Dto\AbstractDemand
 */
class AbstractDemandTest extends \Nimut\TestingFramework\TestCase\UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Dto\AbstractDemand
     */
    protected $fixture;

    protected function setUp(): void
    {
        $this->fixture = new \DWenzel\T3events\Domain\Model\Dto\AbstractDemand();
    }

    protected function tearDown(): void
    {
        unset($this->fixture);
    }

    /**
     * @test
     * @covers ::getLimit
     */
    public function getLimitReturnsInitialValueForInteger()
    {
        $this->assertSame(100, $this->fixture->getLimit());
    }

    /**
     * @test
     * @covers ::setLimit
     */
    public function setLimitForIntegerSetsLimit()
    {
        $this->fixture->setLimit(3);
        $this->assertSame(3, $this->fixture->getLimit());
    }

    /**
     * @test
     * @covers ::setLimit
     */
    public function setLimitCastsStringToInteger()
    {
        $this->fixture->setLimit('2');
        $this->assertInternalType(
            'int',
            $this->fixture->getLimit()
        );
    }

    /**
     * @test
     * @covers ::setLimit
     */
    public function setLimitValidatesLimitGreaterThanZero()
    {
        $this->fixture->setLimit(-1);
        $this->assertSame(
            100,
            $this->fixture->getLimit()
        );
    }

    /**
     * @test
     * @covers ::getOffset
     */
    public function getOffsetReturnsInitialNull()
    {
        $this->assertNull($this->fixture->getOffset());
    }

    /**
     * @test
     * @covers ::setOffset
     */
    public function setOffsetSetsDefaultValueZeroForInteger()
    {
        $this->fixture->setOffset();
        $this->assertSame(
            0,
            $this->fixture->getOffset());
    }

    /**
     * @test
     * @covers ::setOffset
     */
    public function setOffsetSetsOffsetForInteger()
    {
        $this->fixture->setOffset(99);
        $this->assertSame(
            99,
            $this->fixture->getOffset());
    }

    /**
     * @test
     * @covers ::getSortDirection
     */
    public function getSortDirectionReturnsInitialNull()
    {
        $this->assertNull($this->fixture->getSortDirection());
    }

    /**
     * @test
     * @covers ::setSortDirection
     */
    public function setSortDirectionForStringSetsSort()
    {
        $this->fixture->setSortDirection('baz');
        $this->assertSame(
            'baz',
            $this->fixture->getSortDirection()
        );
    }

    /**
     * @test
     * @covers ::getSortBy
     */
    public function getSortByReturnsInitiallyNull()
    {
        $this->assertNull($this->fixture->getSortBy());
    }

    /**
     * @test
     * @covers ::setSortBy
     */
    public function setSortByForStringSetsSortBy()
    {
        $this->fixture->setSortBy('my.sort.string.with.dots');
        $this->assertSame(
            'my.sort.string.with.dots',
            $this->fixture->getSortBy()
        );
    }

    /**
     * @test
     * @covers ::getStoragePages
     */
    public function getStoragePagesReturnsInitialNull()
    {
        $this->assertNull($this->fixture->getStoragePages());
    }

    /**
     * @test
     * @covers ::setStoragePages
     */
    public function setStoragePagesForStringSetsStoragePages()
    {
        $this->fixture->setStoragePages('15,78,39');
        $this->assertSame('15,78,39', $this->fixture->getStoragePages());
    }

    /**
     * @test
     * @covers ::getUidList
     */
    public function getUidListReturnsInitialNull()
    {
        $this->assertNull($this->fixture->getUidList());
    }

    /**
     * @test
     * @covers ::setUidList
     */
    public function setUidListForStringSetsUidList()
    {
        $this->fixture->setUidList('1,3,5');
        $this->assertSame('1,3,5', $this->fixture->getUidList());
    }

    /**
     * @test
     * @covers ::getCategoryConjunction
     */
    public function getCategoryConjunctionReturnsInitialNull()
    {
        $this->assertEquals(
            null,
            $this->fixture->getCategoryConjunction()
        );
    }

    /**
     * @test
     * @covers ::setCategoryConjunction
     */
    public function setCategoryConjunctionForStringSetsCategoryConjunction()
    {
        $this->fixture->setCategoryConjunction('asc');

        $this->assertSame(
            'asc',
            $this->fixture->getCategoryConjunction()
        );
    }

    /**
     * @test
     * @covers ::getConstraintsConjunction
     */
    public function getConstraintsConjunctionReturnsInitialNull()
    {
        $this->assertEquals(
            null,
            $this->fixture->getConstraintsConjunction()
        );
    }

    /**
     * @test
     * @covers ::setConstraintsConjunction
     */
    public function setConstraintsConjunctionForStringSetsConstraintsConjunction()
    {
        $conjunction = 'foo';
        $this->fixture->setConstraintsConjunction($conjunction);

        $this->assertSame(
            $conjunction,
            $this->fixture->getConstraintsConjunction()
        );
    }

    /**
     * @test
     * @covers ::getOrder
     */
    public function getOrderReturnsInitialNull()
    {
        $this->assertEquals(
            null,
            $this->fixture->getOrder()
        );
    }

    /**
     * @test
     * @covers ::setOrder
     */
    public function setOrderForStringSetsCategoryConjunction()
    {
        $order = 'fieldA|asc,fieldB|desc';
        $this->fixture->setOrder($order);

        $this->assertSame(
            $order,
            $this->fixture->getOrder()
        );
    }
}
