<?php
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

namespace DWenzel\T3events\Utility;

/**
 * Interface SettingsInterface
 * Provides constants for common configuration keys
 */
interface SettingsInterface
{
    const __IDENTITY = '__identity';
    const ATTACHMENT = 'attachment';
    const ATTACHMENTS = 'attachments';
    const CONFIG = 'config';
    const EVENTS = 'events';
    const DEMAND = 'demand';
    const FORMAT = 'format';
    const FORWARD = 'forward';
    const FROM_EMAIL = 'fromEmail';
    const FOLDER_NAME = 'folderName';
    const FILES = 'files';
    const FILTER = 'filter';
    const MODULES = 'modules';
    const NOTIFICATIONS = 'notifications';
    const OVERWRITE_DEMAND = 'overwriteDemand';
    const OBJECTS = 'objects';
    const PAGE_RENDERER = 'pageRenderer';
    const PATH = 'path';
    const PATHS = 'paths';
    const PATH_SEPARATOR = '/';
    const PERSISTENCE = 'persistence';
    const REDIRECT = 'redirect';
    const REPOSITORY = 'repository';
    const REQUIRE_JS = 'requireJs';
    const SENDER_NAME = 'senderName';
    const SETTINGS = 'settings';
    const STORAGE_PID = 'storagePid';
    const SUBJECT = 'subject';
    const TEMPLATE = 'template';
    const TO_EMAIL = 'toEmail';
    const TABLE_EVENTS = 'tx_t3events_domain_model_event';
}
