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
    public const __IDENTITY = '__identity';
    public const ACTION_NAME = 'actionName';
    public const ALL = 'all';
    public const ATTACHMENT = 'attachment';
    public const ATTACHMENTS = 'attachments';
    public const ARGUMENTS = 'arguments';
    public const AUDIENCES = 'audiences';
    public const CONFIG = 'config';
    public const CONTROLLER_NAME = 'controllerName';
    public const DATE_FORMAT_SHORT = 'dateFormatShort';
    public const EDIT = 'edit';
    public const END_DATE = 'endDate';
    public const EVENTS = 'events';
    public const EVENT_TYPES = 'eventTypes';
    public const ERROR_HANDLING = 'errorHandling';
    public const EXTENSION_KEY = 't3events';
    public const DEMAND = 'demand';
    public const FORMAT = 'format';
    public const FORWARD = 'forward';
    public const FROM_EMAIL = 'fromEmail';
    public const FOLDER_NAME = 'folderName';
    public const FUTURE_ONLY = 'futureOnly';
    public const FILES = 'files';
    public const FILTER = 'filter';
    public const LEGACY_KEY_GENRE = 'genre';
    public const GENRES = 'genres';
    public const ID = 'id';
    public const KEY_EXTENSION_NAME = 'extensionName';
    public const MODULE = 'module';
    public const MODULES = 'modules';
    public const MODULE_TOKEN_KEY = 'moduleToken';
    public const NOTIFICATIONS = 'notifications';
    public const OVERWRITE_DEMAND = 'overwriteDemand';
    public const RESET_DEMAND = 'resetDemand';
    public const OBJECTS = 'objects';
    public const PAGE_RENDERER = 'pageRenderer';
    public const PAST_ONLY = 'pastOnly';
    public const PATH = 'path';
    public const PATHS = 'paths';
    public const PATH_SEPARATOR = '/';
    public const PERIODS = 'periods';
    public const PERSISTENCE = 'persistence';
    public const RECORD = 'record';
    public const REDIRECT = 'redirect';
    public const REDIRECT_URI = 'redirectUri';
    public const REPOSITORY = 'repository';
    public const REQUIRE_JS = 'requireJs';
    public const RETURN_URL = 'returnUrl';

    /**
     * Modules Registration
     */
    public const MAIN_MODULE_EVENTS = 'T3eventsEvents';

    /**
     * routes identifiers are generated during registration
     * @see ext_tables.php
     */
    public const ROUTE_EVENT_MODULE = 'T3eventsEvents_T3eventsM1';
    public const ROUTE_SCHEDULE_MODULE = 'T3eventsEvents_T3eventsM2';

    /**
     * Core route for convenience
     */
    public const ROUTE_EDIT_RECORD_MODULE = 'record_edit';

    public const SENDER_NAME = 'senderName';
    public const SETTINGS = 'settings';
    public const SORT_DIRECTION = 'sortDirection';
    public const SPECIFIC = 'specific';
    public const START_DATE = 'startDate';
    public const STORAGE_PID = 'storagePid';
    public const SUBJECT = 'subject';
    public const TABLE = 'table';
    public const TEMPLATE = 'template';
    public const TO_EMAIL = 'toEmail';
    public const TOKEN_KEY = 'token';
    public const TABLE_AUDIENCE = 'tx_t3events_domain_model_audience';
    public const TABLE_COMPANY = 'tx_t3events_domain_model_company';
    public const TABLE_EVENTS = 'tx_t3events_domain_model_event';
    public const TABLE_EVENT_LOCATION = 'tx_t3events_domain_model_eventlocation';
    public const TABLE_EVENT_TYPE = 'tx_t3events_domain_model_eventtype';
    public const TABLE_GENRE = 'tx_t3events_domain_model_genre';
    public const TABLE_NOTIFICATION = 'tx_t3events_domain_model_notification';
    public const TABLE_ORGANIZER = 'tx_t3events_domain_model_organizer';
    public const TABLE_PERFORMANCE_STATUS = 'tx_t3events_domain_model_performancestatus';
    public const TABLE_SCHEDULES = 'tx_t3events_domain_model_performance';
    public const TABLE_TASK = 'tx_t3events_domain_model_task';
    public const TABLE_VENUE = 'tx_t3events_domain_model_venue';
    public const TABLE_TICKET_CLASS = 'tx_t3events_domain_model_ticketclass';
    public const TRANSLATION_FILE_PATH = 'EXT:t3events/Resources/Private/Language/';
    public const TRANSLATION_FILE_FRONTEND = 'LLL:EXT:t3events/Resources/Private/Language/locallang.xlf';
    public const TRANSLATION_FILE_BACKEND = 'LLL:EXT:t3events/Resources/Private/Language/locallang_be.xlf';
    public const TRANSLATION_FILE_DB = 'LLL:EXT:t3events/Resources/Private/Language/locallang_db.xlf';
    public const VENUES = 'venues';
    public const FILTER_OPTIONS = 'filterOptions';
    public const EVENT_FORMATS = 'eventFormats';
    public const QUICK_MENU = 'quickMenu';
    public const PERFORMANCE = 'performance';
    public const SECTORS = 'sectors';
    public const DEPARTMENTS = 'departments';
    public const PERFORMANCES = 'performances';
}
