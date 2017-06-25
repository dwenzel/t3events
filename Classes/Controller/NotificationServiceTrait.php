<?php

namespace DWenzel\T3events\Controller;

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
use DWenzel\T3events\Service\NotificationService;

/**
 * Class NotificationServiceTrait
 */
trait NotificationServiceTrait
{
    /**
     * Notification Service
     *
     * @var \DWenzel\T3events\Service\NotificationService
     */
    protected $notificationService;

    /**
     * injects the NotificationService
     *
     * @param \DWenzel\T3events\Service\NotificationService $notificationService
     * @return void
     */
    public function injectNotificationService(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
}
