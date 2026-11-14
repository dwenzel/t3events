<?php

namespace DWenzel\T3events\ViewHelpers\Format;

use TYPO3\CMS\Core\Localization\DateFormatter;

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

trait DateRangeTrait
{
    /**
     * Get date range from timestamps
     *
     * @param array<int, int> $timestamps An ordered array of timestamps
     */
    protected function getDateRange(array $timestamps): string
    {
        $format = static::DEFAULT_DATE_FORMAT;

        $endFormat = $this->arguments['endFormat'] ?? null;
        $startFormat = $this->arguments['startFormat'] ?? null;
        $glue = $this->arguments['glue'] ?? null;

        if (!empty($this->arguments['format'])) {
            $format = $this->arguments['format'];
        }
        if (empty($startFormat)) {
            $startFormat = $format;
        }
        if (empty($endFormat)) {
            $endFormat = $format;
        }
        if (empty($glue)) {
            $glue = static::DEFAULT_GLUE;
        }
        // Formats containing % are strftime formats; they are rendered with the
        // ICU based DateFormatter of TYPO3, since strftime() is deprecated in PHP 8.1.
        $useStrftimeFormat = str_contains((string) $startFormat, '%')
            && str_contains((string) $endFormat, '%');

        $dateRange = $this->formatTimestamp((string) $startFormat, $timestamps[0], $useStrftimeFormat);

        if (count($timestamps) > 1) {
            $dateRange .= $glue . $this->formatTimestamp((string) $endFormat, end($timestamps), $useStrftimeFormat);
        }

        return $dateRange;
    }

    protected function formatTimestamp(string $format, int $timestamp, bool $useStrftimeFormat): string
    {
        if ($useStrftimeFormat) {
            return (new DateFormatter())->strftime($format, $timestamp);
        }
        return date($format, $timestamp);
    }
}
