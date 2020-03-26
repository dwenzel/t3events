<?php

namespace DWenzel\T3events\Configuration;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
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

use DWenzel\T3events\Configuration\Module\Event;
use DWenzel\T3events\Configuration\Module\Main;
use DWenzel\T3events\Configuration\Module\Schedule;
use DWenzel\T3events\Configuration\Plugin\Combined;
use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class ExtensionConfiguration
 * Configuration for extension t3events_course
 */
class ExtensionConfiguration extends \DWenzel\T3events\Configuration\Base\ExtensionConfiguration
{
    public const EXTENSION_KEY = 't3events';
    public const VENDOR = 'DWenzel';

    protected const MODULES_TO_REGISTER = [
        // order matters, register (empty) main module first
        Main::class,
        Event::class,
        Schedule::class
    ];

    protected const PLUGINS_TO_REGISTER = [
        Combined::class
    ];

    public const TABLES_ALLOWED_ON_STANDARD_PAGES = [
        SI::TABLE_AUDIENCE,
        SI::TABLE_COMPANY,
        SI::TABLE_EVENTS,
        SI::TABLE_EVENT_LOCATION,
        SI::TABLE_EVENT_TYPE,
        SI::TABLE_GENRE,
        SI::TABLE_NOTIFICATION,
        SI::TABLE_ORGANIZER,
        SI::TABLE_PERFORMANCE_STATUS,
        SI::TABLE_SCHEDULES,
        SI::TABLE_TASK,
        SI::TABLE_VENUE,
        SI::TABLE_TICKET_CLASS
    ];

    protected const DESCRIPTION_FILE_PREFIX = SI::TRANSLATION_FILE_PATH . 'locallang_csh_tx_t3events_domain_model_';
    protected const DESCRIPTION_FILE_SUFFIX = '.xml';
    public const LOCALIZED_TABLE_DESCRIPTION = [
        'tt_content.pi_flexform.t3events_events.list' => 'EXT:t3events/Resources/Private/Language/locallang_csh_flexform.xml',
        SI::TABLE_AUDIENCE => self::DESCRIPTION_FILE_PREFIX . 'audience' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_COMPANY => self::DESCRIPTION_FILE_PREFIX . 'company' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_EVENTS => self::DESCRIPTION_FILE_PREFIX . 'event' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_EVENT_LOCATION => self::DESCRIPTION_FILE_PREFIX . 'eventlocation' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_EVENT_TYPE => self::DESCRIPTION_FILE_PREFIX . 'eventtype' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_GENRE => self::DESCRIPTION_FILE_PREFIX . 'genre' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_NOTIFICATION => self::DESCRIPTION_FILE_PREFIX . 'notification' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_ORGANIZER => self::DESCRIPTION_FILE_PREFIX . 'organizer' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_PERFORMANCE_STATUS => self::DESCRIPTION_FILE_PREFIX . 'performancestatus' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_SCHEDULES => self::DESCRIPTION_FILE_PREFIX . 'performance' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_TASK => self::DESCRIPTION_FILE_PREFIX . 'task' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_VENUE => self::DESCRIPTION_FILE_PREFIX . 'venue' . self::DESCRIPTION_FILE_SUFFIX,
        SI::TABLE_TICKET_CLASS => self::DESCRIPTION_FILE_PREFIX . 'ticketclass' . self::DESCRIPTION_FILE_SUFFIX
    ];

    public const SVG_ICONS_TO_REGISTER = [
        'ext-t3events-event' => 'tx_t3events_domain_model_event.svg',
        'ext-t3events-performance' => 'tx_t3events_domain_model_performance.svg',
    ];
}
