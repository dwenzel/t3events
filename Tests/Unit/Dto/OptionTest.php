<?php

namespace DWenzel\T3events\Tests\Unit\Dto;

use DWenzel\T3events\Dto\Option;
use Nimut\TestingFramework\TestCase\UnitTestCase;

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
 * Class OptionTest
 */
class OptionTest extends UnitTestCase
{
    /**
     * @var Option
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new Option();
    }

    public function testGetValueInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getValue()
        );
    }

    public function testValueCanBeSet()
    {
        $value = 'foo';
        $this->subject->setValue($value);
        $this->assertSame(
            $value,
            $this->subject->getValue()
        );
    }

    public function testGetLabelInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getLabel()
        );
    }

    public function testLabelCanBeSet()
    {
        $label = 'bar';
        $this->subject->setLabel($label);
        $this->assertSame(
            $label,
            $this->subject->getLabel()
        );
    }

    public function testSetValueReturnsInstance()
    {
        $this->assertSame(
            $this->subject,
            $this->subject->setValue()
        );
    }
}
