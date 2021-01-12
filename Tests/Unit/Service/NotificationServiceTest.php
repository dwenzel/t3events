<?php
namespace DWenzel\T3events\Tests\Unit\Service;

use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Mail\MailMessage;
use Nimut\TestingFramework\TestCase\UnitTestCase;
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
    use MockObjectManagerTrait;

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
            NotificationService::class, ['dummy']
        );
        $this->objectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($this->objectManager);
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

        $mockMessage = $this->getMockMailMessage();
        $this->objectManager->expects(self::once())
            ->method('get')
            ->willReturn($mockMessage);

        $mockMessage->expects(self::once())
            ->method('setTo')
            ->with($expectedRecipients);

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
            NotificationService::class, ['dummy', 'buildTemplateView']
        );
        $this->subject->injectObjectManager($this->objectManager);

        $mockTemplateView = $this->getMockBuilder(StandaloneView::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject->expects(self::once())
            ->method('buildTemplateView')
            ->will(self::returnValue($mockTemplateView));

        $mockMessage = $this->getMockMailMessage();
        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(MailMessage::class)
            ->willReturn($mockMessage);

        $mockMessage->expects(self::once())
            ->method('setTo')
            ->with($expectedRecipients);
        $this->subject->notify(
            $recipient,
            'bar@baz.foo',
            'foo',
            'bar',
            null,
            'baz'
        );
    }

    /**
     * @return MailMessage|MockObject
     */
    protected function getMockMailMessage()
    {
        $message = $this->getMockBuilder(MailMessage::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'setTo',
                    'setBody',
                    'send',
                    'setFrom',
                    'setSubject'
                ]
            )->getMock();
        $message->method('setTo')->willReturn($message);
        $message->method('send')->willReturn(true);
        $message->method('setFrom')->willReturn($message);
        $message->method('setSubject')->willReturn($message);

        return $message;
    }
}
