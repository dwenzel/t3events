<?php

/**
 * This file is part of the TYPO3 CMS project.
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

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 */
class ext_update
{

    /**
     * @var \DWenzel\T3events\Update\MigrateTaskRecords
     */
    protected $taskUpdater;

    /**
     * @var \DWenzel\T3events\Update\MigratePluginRecords
     */
    protected $pluginUpdater;

    /**
     * Array of flash messages (params) array[][status,title,message]
     *
     * @var array
     */
    protected $messageArray = [];

    public function __construct()
    {
        $this->taskUpdater = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\DWenzel\T3events\Update\MigrateTaskRecords::class);
        $this->pluginUpdater = GeneralUtility::makeInstance(\DWenzel\T3events\Update\MigratePluginRecords::class);
    }

    /**
     * Main update function called by the extension manager.
     *
     * @return string
     */
    public function main()
    {
        $this->processUpdates();
        return $this->generateOutput();
    }

    /**
     * The actual update function.
     *
     * @return void
     */
    protected function processUpdates()
    {
        $messages = [];
        $dbQueries = [];
        $this->taskUpdater->performUpdate($dbQueries, $messages);
        $this->pluginUpdater->performUpdate($dbQueries, $messages);

        $this->messageArray = $messages;
    }

    /**
     * Generates output by using flash messages
     *
     * @return string
     */
    protected function generateOutput()
    {
        $output = '';
        foreach ($this->messageArray as $messageItem) {
            /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                $messageItem[2],
                $messageItem[1],
                $messageItem[0]);
            $output .= $flashMessage->render();
        }
        return $output;
    }

    /**
     * Called by the extension manager to determine if the update menu entry
     * should by showed.
     *
     * @return bool
     */
    public function access()
    {
        $description = '';
        $showMenu = ($this->taskUpdater->checkForUpdate($description) || $this->pluginUpdater->checkForUpdate($description));
        return $showMenu;
    }

    /**
     * Renders a flash message.
     *
     * @param FlashMessage $message
     * @return string The flash message as HTML.
     */
    public function renderFlashMessage($message)
    {
        $title = '';
        if (!empty($message->getTitle())) {
            $title = '<h4 class="alert-title">' . $message->getTitle() . '</h4>';
        }
        $message = '
			<div class="alert ' . $message->getClass() . '">
				<div class="media">
					<div class="media-left">
						<span class="fa-stack fa-lg">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-' . $message->getIconName() . ' fa-stack-1x"></i>
						</span>
					</div>
					<div class="media-body">
						' . $title . '
						<div class="alert-message">' . $message->getMessage() . '</div>
					</div>
				</div>
			</div>';
        return $message;
    }
}
