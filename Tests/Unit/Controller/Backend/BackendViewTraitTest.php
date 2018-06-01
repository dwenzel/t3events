<?php

namespace DWenzel\T3events\Tests\Unit\Controller\Backend;

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

use DWenzel\T3events\Controller\Backend\BackendViewTrait;
use DWenzel\T3events\View\ConfigurableViewInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Class BackendViewTraitTest
 */
class BackendViewTraitTest extends UnitTestCase
{
    /**
     * @var BackendViewTrait|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(BackendViewTrait::class)
            ->getMockForTrait();
    }

    /**
     * @test
     */
    public function initializeViewAppliesSettingsToInstancesOfConfigurableViewInterface() {
        $settings = [
            ConfigurableViewInterface::SETTINGS_KEY => ['foo']
        ];

        $this->inject(
            $this->subject,
            'settings',
            $settings
        );
        /** @var ConfigurableViewInterface|ViewInterface|\PHPUnit_Framework_MockObject_MockObject $mockView */
        $mockView = $this->getMockBuilder(ConfigurableViewInterface::class)
            ->setMethods(['apply'])
            ->getMockForAbstractClass();
        $mockView->expects($this->once())->method('apply')
            ->with($settings[ConfigurableViewInterface::SETTINGS_KEY]);
        $this->subject->initializeView($mockView);
    }
}
