<?php
namespace DWenzel\T3events\Tests\Unit\Service;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use DWenzel\T3events\Domain\Model\Notification;
use DWenzel\T3events\Service\NotificationService;

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
class NotificationServiceTest extends UnitTestCase
{
    /**
     * @var NotificationService
     */
    protected $subject;

    /**
     * set up subject
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            NotificationService::class,['dummy']
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ObjectManager
     */
    protected function mockObjectManager()
    {
        $mockObjectManager = $this->getMock(
            ObjectManager::class, ['get']
        );
        $this->inject(
            $this->subject,
            'objectManager',
            $mockObjectManager
        );

        return $mockObjectManager;
    }

    /**
     * Provides recipients
     *
     * @return array
     */
    public function recipientDataProvider()
    {
        return [
            [ 'foo@bar.baz', ['foo@bar.baz']],
            ['foo,bar', ['foo', 'bar']]
        ];

    }
    /**
     * @test
     * @param string $recipientArgument
     * @param array $expectedRecipients
     * @dataProvider recipientDataProvider
     */
    public function sendSetsRecipients($recipientArgument, $expectedRecipients)
    {
        $notification = new Notification();
        $notification->setRecipient($recipientArgument);

        $mockMessage = $this->getMock(
            MailMessage::class, ['setTo', 'send']
        );
        $mockObjectManager = $this->mockObjectManager();
        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(MailMessage::class)
            ->will($this->returnValue($mockMessage));

        $mockMessage->expects($this->once())
            ->method('setTo')
            ->with($expectedRecipients)
            ->will($this->returnValue($mockMessage));

        $this->subject->send($notification);
    }

    /**
     * @test
     * @param string $recipient
     * @param array $expectedRecipients
     * @dataProvider recipientDataProvider
     */
    public function notifySetsRecipients($recipient, $expectedRecipients)
    {
        $this->subject = $this->getAccessibleMock(
            \DWenzel\T3events\Service\NotificationService::class,['dummy', 'buildTemplateView']
        );

        $mockTemplateView = $this->getMock(
            StandaloneView::class, [], [], '', false
        );

        $this->subject->expects($this->once())
            ->method('buildTemplateView')
            ->will($this->returnValue($mockTemplateView));

        $mockMessage = $this->getMock(
            MailMessage::class, ['setTo', 'send']
        );
        $mockObjectManager = $this->mockObjectManager();
        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(MailMessage::class)
            ->will($this->returnValue($mockMessage));

        $mockMessage->expects($this->once())
            ->method('setTo')
            ->with($expectedRecipients)
            ->will($this->returnValue($mockMessage));

        $this->subject->notify(
            $recipient,
            'bar@baz.foo',
            'foo',
            'bar',
            null,
            'baz'
        );
    }
}
