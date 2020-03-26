<?php

namespace DWenzel\T3events\ViewHelpers\Format;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Dirk Wenzel <wenzel@cps-it.de>
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;


/**
 * Class AbstractDateRangeViewHelper
 */
class AbstractDateRangeViewHelper extends AbstractViewHelper
{
    use DateRangeTrait;
    const ARGUMENT_FORMAT_DESCRIPTION = 'A string describing the date format for start and end date. Default is \'d.m.Y\'. See PHP date() function for options. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_STARTFORMAT_DESCRIPTION = 'A string describing the date format for start date. Default is \'d.m.Y\'. See PHP date() function for options. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_ENDFORMAT_DESCRIPTION = 'A string describing the date format for end date. Default is \'d.m.Y\'. See PHP date() function for options. If the string contains a % strftime() function will be used instead.';
    const ARGUMENT_GLUE_DESCRIPTION = 'Glue between start and end date if applicable. Default is \' - \' ';
    const DEFAULT_DATE_FORMAT = 'd.m.Y';
    const DEFAULT_GLUE = ' - ';
}
