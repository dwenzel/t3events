<?php

namespace DWenzel\T3events\Tests\Unit\Controller;

use DWenzel\T3events\Controller\FlashMessageTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
class FlashMessageTraitTest extends UnitTestCase
{
    /**
     * @var FlashMessageTrait
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            FlashMessageTrait::class
        );
    }

    /**
     * @test
     */
    public function addFlashMessageThrowsExceptionForMissingMessageBody()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1243258395);
        $this->subject->addFlashMessage(5);
    }

    /**
     * @test
     */
    public function addFlashMessageEnqueuesMessage()
    {
        $messageBody = 'foo';
        $messageTitle = 'bar';
        $severity = AbstractMessage::ERROR;
        $storeInSession = false;
        $expectedMessage = GeneralUtility::makeInstance(
            FlashMessage::class, $messageBody, $messageTitle, $severity, $storeInSession
        );

        $mockMessageQueue = $this->getMockFlashMessageQueue(['enqueue']);
        // getFlashMessageQueue() is provided by ActionController
        $this->subject = $this->getMockBuilder(FlashMessageTrait::class)
            ->addMethods(['getFlashMessageQueue'])
            ->getMockForTrait();
        $this->subject->method('getFlashMessageQueue')->willReturn($mockMessageQueue);

        $mockMessageQueue->expects($this->once())
            ->method('enqueue')
            ->with($this->equalTo($expectedMessage));
        $this->subject->addFlashMessage(
            $messageBody, $messageTitle, $severity, $storeInSession
        );
    }

    /**
     * @param array $methods Methods to mock
     * @return FlashMessageQueue|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockFlashMessageQueue(array $methods = [])
    {
        return $this->getMockBuilder(FlashMessageQueue::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }
}
