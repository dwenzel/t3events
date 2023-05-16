<?php

namespace DWenzel\T3events\Tests\Unit\Dto;

use DWenzel\T3events\Domain\Repository\DemandedRepositoryInterface;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Dto\GenreFilter;
use DWenzel\T3events\Dto\OptionsTrait;
use DWenzel\T3events\Utility\SettingsInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class OptionsTraitTest
 */
class OptionsTraitTest extends UnitTestCase
{
    /**
     * @var GenreFilter|MockObject
     */
    protected $subject;

    /**
     * @var GenreRepository|MockObject
     */
    protected $repository;

    /**
     * @var QueryResultInterface
     */
    protected $queryResult;

    protected function setUp(): void
    {
        $this->subject = $this->getMockBuilder(OptionsTrait::class)
            ->setMethods(['getOptionRepository'])
            ->getMockForTrait();
        $this->repository = $this->getMockBuilder(DemandedRepositoryInterface::class)
            ->setMethods(['findAll', 'findMultipleByUid'])
            ->getMockForAbstractClass();
        $this->subject->method('getOptionRepository')
            ->willReturn($this->repository);

        $this->queryResult = $this->getMockBuilder(QueryResultInterface::class)
            ->getMockForAbstractClass();
    }

    public function testGetOptionsInitiallyReturnsIterable()
    {
        $this->assertTrue(
            is_iterable($this->subject->getOptions())
        );
    }

    public function testCountInitiallyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->count()
        );
    }

    public function testConfigureGetsAllFromRepository()
    {
        $config = [SettingsInterface::ALL];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($this->queryResult);

        $this->subject->configure($config);
        $this->assertSame(
            $this->queryResult,
            $this->subject->getOptions()
        );
    }

    public function testConfigureGetsMultipleByUidFromRepository()
    {
        $config = ['1,4'];
        $this->repository->expects($this->once())
            ->method('findMultipleByUid')
            ->with($config[0], GenreFilter::DEFAULT_SORT_FIELD)
            ->willReturn($this->queryResult);

        $this->subject->configure($config);
        $this->assertSame(
            $this->queryResult,
            $this->subject->getOptions()
        );
    }
}
