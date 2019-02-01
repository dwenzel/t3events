<?php

namespace DWenzel\T3events\Tests\Controller;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Controller\NotificationServiceTrait;
use DWenzel\T3events\Service\NotificationService;
use Nimut\TestingFramework\TestCase\UnitTestCase;


/**
 * Class NotificationServiceTraitTest
 */
class NotificationServiceTraitTest extends UnitTestCase
{
    /**
     * @var NotificationServiceTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockBuilder(NotificationServiceTrait::class)->getMockForTrait();
    }

    /**
     * @test
     */
    public function notificationServiceCanBeInjected()
    {
        /** @var NotificationService|\PHPUnit_Framework_MockObject_MockObject $notificationService */
        $notificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();

        $this->subject->injectNotificationService($notificationService);

        $this->assertAttributeSame(
            $notificationService,
            'notificationService',
            $this->subject
        );
    }
}
