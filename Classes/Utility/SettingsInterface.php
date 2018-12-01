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
    const ACTION_NAME = 'actionName';
    const ALL = 'all';
    const ATTACHMENT = 'attachment';
    const ATTACHMENTS = 'attachments';
    const ARGUMENTS = 'arguments';
    const CONFIG = 'config';
    const CONTROLLER_NAME = 'controllerName';
    const DATE_FORMAT_SHORT = 'dateFormatShort';
    const END_DATE = 'endDate';
    const EVENTS = 'events';
    const EVENT_TYPES = 'eventTypes';
    const ERROR_HANDLING = 'errorHandling';
    const EXTENSION_KEY = 't3events';
    const DEMAND = 'demand';
    const FORMAT = 'format';
    const FORWARD = 'forward';
    const FROM_EMAIL = 'fromEmail';
    const FOLDER_NAME = 'folderName';
    const FUTURE_ONLY = 'futureOnly';
    const FILES = 'files';
    const FILTER = 'filter';
    const LEGACY_KEY_GENRE = 'genre';
    const GENRES = 'genres';
    const KEY_EXTENSION_NAME = 'extensionName';
    const MODULES = 'modules';
    const NOTIFICATIONS = 'notifications';
    const OVERWRITE_DEMAND = 'overwriteDemand';
    const OBJECTS = 'objects';
    const PAGE_RENDERER = 'pageRenderer';
    const PAST_ONLY = 'pastOnly';
    const PATH = 'path';
    const PATHS = 'paths';
    const PATH_SEPARATOR = '/';
    const PERSISTENCE = 'persistence';
    const REDIRECT = 'redirect';
    const REDIRECT_URI = 'redirectUri';
    const REPOSITORY = 'repository';
    const REQUIRE_JS = 'requireJs';
    const SENDER_NAME = 'senderName';
    const SETTINGS = 'settings';
    const SORT_DIRECTION = 'sortDirection';
    const SPECIFIC = 'specific';
    const START_DATE = 'startDate';
    const STORAGE_PID = 'storagePid';
    const SUBJECT = 'subject';
    const TEMPLATE = 'template';
    const TO_EMAIL = 'toEmail';
    const TABLE_EVENTS = 'tx_t3events_domain_model_event';
    const TABLE_SCHEDULES = 'tx_t3events_domain_model_performance';
    const TRANSLATION_FILE_PATH = '';
    const TRANSLATION_FILE_FRONTEND = 'LLL:EXT:t3events/Resources/Private/Language/locallang.xlf';
    const TRANSLATION_FILE_BACKEND = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xml';
    const TRANSLATION_FILE_DB = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf';
    const VENUES = 'venues';
}
