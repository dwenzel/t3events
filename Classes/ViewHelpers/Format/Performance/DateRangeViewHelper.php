<?php

namespace DWenzel\T3events\ViewHelpers\Format\Performance;

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

use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\ViewHelpers\Format\AbstractDateRangeViewHelper;

/**
 * Class DateRangeViewHelper
 */
class DateRangeViewHelper extends AbstractDateRangeViewHelper
{
    const ARGUMENT_PERFORMANCE_DESCRIPTION = 'Performance for which the date range should be rendered.';

    /**
     * @var \DWenzel\T3events\Domain\Model\Performance
     */
    protected $performance;

    public function initializeArguments()
    {
        $this->registerArgument('performance', Performance::class, static::ARGUMENT_PERFORMANCE_DESCRIPTION, true);
        $this->registerArgument('format', 'string', static::ARGUMENT_FORMAT_DESCRIPTION, false, static::DEFAULT_DATE_FORMAT);
        $this->registerArgument('startFormat', 'string', static::ARGUMENT_STARTFORMAT_DESCRIPTION, false, static::DEFAULT_DATE_FORMAT);
        $this->registerArgument('endFormat', 'string', static::ARGUMENT_ENDFORMAT_DESCRIPTION, false, static::DEFAULT_DATE_FORMAT);
        $this->registerArgument('glue', 'string', static::ARGUMENT_GLUE_DESCRIPTION, false, static::DEFAULT_GLUE);
    }

    /**
     *
     */
    public function render()
    {
        $this->performance = $this->arguments['performance'];
        $this->initialize();

        return $this->getDateRange($this->getTimestamps());
    }


    /**
     * @return array An array of timestamps
     */
    protected function getTimestamps()
    {
        $startDate = $this->performance->getDate();
        $timestamps = [$startDate->getTimestamp()];
        $endDate = $this->performance->getEndDate();

        if (!empty($endDate) && $endDate > $startDate) {
            $timestamps[] = $endDate->getTimestamp();
        }

        return $timestamps;
    }
}
