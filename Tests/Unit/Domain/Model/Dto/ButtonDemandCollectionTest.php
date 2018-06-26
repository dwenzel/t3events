<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;
use DWenzel\T3events\Domain\Model\Dto\ButtonDemand;
use DWenzel\T3events\Domain\Model\Dto\ButtonDemandCollection;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
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

class ButtonDemandCollectionTest extends UnitTestCase
{

    /**
     * @var ButtonDemandCollection|MockObject
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = new ButtonDemandCollection();
    }

    /**
     * @test
     */
    public function getDemandInitiallyReturnsEmptyObjectStorage() {
        $this->assertInstanceOf(
            ObjectStorage::class,
            $this->subject->getDemands()
        );
    }

    /**
     * @test
     */
    public function demandsCanBeSet() {
        $demands = new  ObjectStorage();
        $this->subject->setDemands($demands);
        $this->assertSame(
            $demands,
            $this->subject->getDemands()
        );
    }

    /**
     * @test
     */
    public function demandCanBeAdded() {
        $demand = new ButtonDemand();
        $this->subject->addDemand($demand);
        $this->assertContains(
            $demand,
            $this->subject->getDemands()
        );
    }

    /**
     * @test
     */
    public function demandCanBeRemoved() {
        $demands = new ObjectStorage();
        $demandToBeRemoved = new ButtonDemand();
        $demands->attach($demandToBeRemoved);
        $this->subject->setDemands($demands);

        $this->subject->removeDemand($demandToBeRemoved);
        $this->assertNotContains(
            $demandToBeRemoved,
            $this->subject->getDemands()
        );
    }

    /**
     * @test
     */
    public function constructorCreatesDemandFromSettings() {
        $singleButtonConfig = [
            ButtonDemand::TABLE_KEY => 'foo',
            ButtonDemand::ACTION_KEY => 'bar',
            ButtonDemand::ICON_KEY => 'baz',
            ButtonDemand::LABEL_KEY => 'boom'
        ];
        $settings = [$singleButtonConfig];

        $this->subject = new ButtonDemandCollection($settings);

        $demands = $this->subject->getDemands();
        $demands->rewind();
        /** @var ButtonDemand $firstDemand */
        $firstDemand = $demands->current();
        $this->assertInstanceOf(
            ButtonDemand::class,
            $firstDemand
        );

        $this->assertSame(
            $singleButtonConfig[ButtonDemand::LABEL_KEY],
            $firstDemand->getLabelKey()
        );
        $this->assertSame(
            $singleButtonConfig[ButtonDemand::TABLE_KEY],
            $firstDemand->getTable()
        );
        $this->assertSame(
            $singleButtonConfig[ButtonDemand::ICON_KEY],
            $firstDemand->getIconKey()
        );
        $this->assertSame(
            $singleButtonConfig[ButtonDemand::ACTION_KEY],
            $firstDemand->getAction()
        );
    }
}
