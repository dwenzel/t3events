<?php

namespace DWenzel\T3events\Tests\Unit\ViewHelpers\Be;

use DWenzel\T3events\Utility\SettingsInterface as SI;
use DWenzel\T3events\ViewHelpers\Be\EditUriViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Backend\Routing\UriBuilder;

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
 * Class EditUriViewHelperTest
 */
class EditUriViewHelperTest extends UnitTestCase
{
    /**
     * @var EditUriViewHelper|MockObject
     */
    protected $subject;

    /**
     * @var UriBuilder|MockObject
     */
    protected $uriBuilder;

    public function setUp()
    {
        $this->subject = $this->getMockBuilder(EditUriViewHelper::class)
            ->setMethods(['registerArgument', 'getUriBuilder'])
            ->getMock();
        $this->uriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['buildUriFromRoute'])
            ->getMock();
        $this->subject->method('getUriBuilder')->willReturn($this->uriBuilder);
    }

    public function testArgumentsAreRegistered()
    {
        $this->subject->expects($this->exactly(3))
            ->method('registerArgument')
            ->withConsecutive(
                [SI::TABLE, 'string', EditUriViewHelper::DESCRIPTION_ARGUMENT_TABLE, true],
                [SI::RECORD, 'integer', EditUriViewHelper::DESCRIPTION_ARGUMENT_RECORD, true],
                [SI::MODULE, 'string', EditUriViewHelper::DESCRIPTION_ARGUMENT_MODULE, true]
            );

        $this->subject->initializeArguments();
    }
}
