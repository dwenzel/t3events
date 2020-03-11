<?php

namespace DWenzel\T3events\Configuration\Module;

use DWenzel\T3extensionTools\Configuration\ModuleRegistrationInterface;
use DWenzel\T3extensionTools\Configuration\ModuleRegistrationTrait;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
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
class Main extends DefaultRegistration implements ModuleRegistrationInterface
{
    use ModuleRegistrationTrait;

    static protected $subModuleName = '';
    static protected $mainModuleName = 'events';
    static protected $controllerActions = [];
    static protected $moduleConfiguration = [
        'icon' => 'EXT:t3events/Resources/Public/Icons/module_administration.svg',
        'labels' => 'LLL:EXT:t3events/Resources/Private/Language/locallang_mod_main.xlf',
    ];

}
