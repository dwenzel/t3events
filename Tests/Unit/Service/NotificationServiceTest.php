<?php
namespace DWenzel\T3events\Tests\Unit\Service;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use TYPO3\CMS\Core\Mail\Mailer;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
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
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            NotificationService::class, [], $this->constructorArguments()
        );
    }

    /**
     * @return array<int, MockObject>
     */
    protected function constructorArguments(): array
    {
        return [
            $this->createMock(ViewFactoryInterface::class),
            $this->createMock(Mailer::class),
        ];
    }

    /**
     * Provides recipients
     *
     * @return array
     */
    public static function recipientDataProvider()
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
        $notification->initializeObject();
        $notification->setRecipient($recipientArgument);

        $mockMessage = $this->getMockMailMessage();
        GeneralUtility::addInstance(MailMessage::class, $mockMessage);

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
            NotificationService::class, ['buildTemplateView'], $this->constructorArguments()
        );

        $mockTemplateView = $this->getMockBuilder(ViewInterface::class)
            ->getMockForAbstractClass();

        $this->subject->expects(self::once())
            ->method('buildTemplateView')
            ->will(self::returnValue($mockTemplateView));

        $mockMessage = $this->getMockMailMessage();
        GeneralUtility::addInstance(MailMessage::class, $mockMessage);

        $mockMessage->expects(self::once())
            ->method('setTo')
            ->with($expectedRecipients);
        $this->subject->notify(
            $recipient,
            'bar@baz.foo',
            'foo',
            'bar',
            'baz'
        );
    }

    /**
     * @test
     */
    public function renderPassesFormatAndFolderNameInTheExpectedOrder(): void
    {
        $this->subject = $this->getAccessibleMock(
            NotificationService::class, ['buildTemplateView'], $this->constructorArguments()
        );
        $mockTemplateView = $this->createMock(ViewInterface::class);
        $mockTemplateView->method('render')->willReturn('');

        $this->subject->expects(self::once())
            ->method('buildTemplateView')
            ->with('templateName', 'plain', 'folderName')
            ->willReturn($mockTemplateView);

        $this->subject->render('templateName', 'folderName', 'plain');
    }

    /**
     * @test
     */
    public function getTemplatePathAndFileNameUsesDefaultFolderIfFolderNameIsNull(): void
    {
        $configurationManager = $this->createMock(ConfigurationManagerInterface::class);
        $configurationManager->method('getConfiguration')
            ->willReturn(['view' => ['templateRootPath' => '']]);
        $this->subject->injectConfigurationManager($configurationManager);

        self::assertSame(
            'Email/templateName.html',
            $this->subject->_call('getTemplatePathAndFileName', 'templateName', null)
        );
    }

    /**
     * @test
     */
    public function sendReturnsTrueAndSetsSentAtIfMailerHasSentTheMessage(): void
    {
        $mailer = $this->createMock(Mailer::class);
        $mailer->method('getSentMessage')->willReturn($this->createMock(SentMessage::class));
        $this->subject = $this->getAccessibleMock(
            NotificationService::class, [], [$this->createMock(ViewFactoryInterface::class), $mailer]
        );
        $notification = new Notification();
        $notification->initializeObject();
        $notification->setRecipient('foo@bar.baz');
        GeneralUtility::addInstance(MailMessage::class, $this->getMockMailMessage());

        self::assertTrue($this->subject->send($notification));
        self::assertInstanceOf(\DateTime::class, $notification->getSentAt());
    }

    /**
     * @test
     */
    public function sendReturnsFalseIfTransportFails(): void
    {
        $mailer = $this->createMock(Mailer::class);
        $mailer->method('send')->willThrowException(new TransportException('failed'));
        $this->subject = $this->getAccessibleMock(
            NotificationService::class, [], [$this->createMock(ViewFactoryInterface::class), $mailer]
        );
        $notification = new Notification();
        $notification->initializeObject();
        $notification->setRecipient('foo@bar.baz');
        GeneralUtility::addInstance(MailMessage::class, $this->getMockMailMessage());

        self::assertFalse($this->subject->send($notification));
        self::assertNull($notification->getSentAt());
    }

    /**
     * @return MailMessage|MockObject
     */
    protected function getMockMailMessage()
    {
        $message = $this->getMockBuilder(MailMessage::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'setTo',
                    'setFrom',
                    'setSubject',
                    'html',
                    'text',
                ]
            )->getMock();
        $message->method('setTo')->willReturn($message);
        $message->method('setFrom')->willReturn($message);
        $message->method('setSubject')->willReturn($message);
        $message->method('html')->willReturn($message);
        $message->method('text')->willReturn($message);

        return $message;
    }
}
