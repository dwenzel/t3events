<?php

namespace DWenzel\T3events\Configuration\Module;

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

use DWenzel\T3extensionTools\Configuration\ModuleRegistrationInterface;
use DWenzel\T3extensionTools\Configuration\ModuleRegistrationTrait;

/**
 * Class Schedule
 */
abstract class Schedule extends DefaultRegistration implements ModuleRegistrationInterface
{
    use ModuleRegistrationTrait;

    static protected $subModuleName = 'm2';
    static protected $controllerActions = [
        'Backend\Schedule' => 'list,show,edit,delete,reset',
    ];
    static protected $moduleConfiguration = [
        'access' => 'user,group',
        'icon' => 'EXT:t3events/Resources/Public/Icons/calendar-blue.svg',
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_m2.xlf',
    ];

}
