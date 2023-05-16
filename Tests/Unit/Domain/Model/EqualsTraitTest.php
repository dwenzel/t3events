<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model;
use DWenzel\T3events\Domain\Model\EqualsTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
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

class EqualsTraitTest extends UnitTestCase
{
    /**
     * @var EqualsTrait | MockObject | AbstractDomainObject
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getMockBuilder(EqualsTrait::class)
            ->setMethods(['__toString'])
            ->getMockForTrait();
    }

    public function testEqualsReturnsFalseForDifferentObject()
    {
        /** @var AbstractDomainObject|MockObject $objectToCompare */
        $objectToCompare = $this->getMockBuilder(AbstractDomainObject::class)
            ->setMethods(['__toString'])
            ->getMockForAbstractClass();
        $objectToCompare->expects($this->once())
            ->method('__toString')
            ->willReturn('foo');

        $this->subject->expects($this->once())
            ->method('__toString')
            ->willReturn('bar');

        $this->assertFalse(
            $this->subject->equals($objectToCompare)
        );
    }

    public function testEqualsReturnsTrueForSameObject()
    {
        $toString = 'foo';

        /** @var AbstractDomainObject|MockObject $objectToCompare */
        $objectToCompare = $this->getMockBuilder(AbstractDomainObject::class)
            ->setMethods(['__toString'])
            ->getMockForAbstractClass();
        $objectToCompare->expects($this->once())
            ->method('__toString')
            ->willReturn($toString);

        $this->subject->expects($this->any())
            ->method('__toString')
            ->willReturn($toString);

        $this->assertTrue(
            $this->subject->equals($objectToCompare)
        );
    }

}
