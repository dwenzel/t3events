<?php
declare(strict_types=1);

namespace DWenzel\T3events\Tests\Unit\Update;

use DWenzel\T3events\Update\LegacyFileFieldsUpdateWizard;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 Dirk Wenzel <wenzel@cps-it.de>
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
class LegacyFileFieldsUpdateWizardTest extends TestCase
{
    /**
     * @var LegacyFileFieldsUpdateWizard
     */
    protected $subject;

    /**
     * @var OutputInterface|MockObject
     */
    protected $output;

    protected function setUp(): void
    {
        $this->subject = new LegacyFileFieldsUpdateWizard();
        $this->output = $this->getMockForAbstractClass(OutputInterface::class);
    }

    public function testGetOutputReturnsOutputInterface(): void
    {
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(
            OutputInterface::class,
            $this->subject->getOutput()
        );
    }

    public function testOutputCanBeSet(): void
    {
        $this->subject->setOutput($this->output);
        self::assertSame(
            $this->output,
            $this->subject->getOutput()
        );
    }

    public function testGetIdentifierReturnsClassConstant(): void
    {
        self::assertSame(
            LegacyFileFieldsUpdateWizard::IDENTIFIER,
            $this->subject->getIdentifier()
        );
    }

    public function testGetTitleReturnsClassConstant(): void
    {
        self::assertSame(
            LegacyFileFieldsUpdateWizard::TITLE,
            $this->subject->getTitle()
        );
    }
    public function testGetDescriptionReturnsClassConstant(): void
    {
        self::assertSame(
            LegacyFileFieldsUpdateWizard::DESCRIPTION,
            $this->subject->getDescription()
        );
    }
    public function testGetPrerequisitesReturnsClassConstant(): void
    {
        self::assertSame(
            LegacyFileFieldsUpdateWizard::PREREQUISITES,
            $this->subject->getPrerequisites()
        );
    }
}
