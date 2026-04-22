<?php

namespace DWenzel\T3events\Dto;

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

use DWenzel\T3events\Controller\TranslateTrait;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PeriodFilter
 */
class PeriodFilter implements FilterInterface
{
    use TranslateTrait;

    const DEFAULT_OPTION_KEYS = [
        SI::FUTURE_ONLY,
        SI::PAST_ONLY,
        SI::ALL,
        SI::SPECIFIC
    ];

    const PREFIX_OPTION_LABEL_KEY = 'label.period.';

    protected $options = [];

    public function getOptions(): iterable
    {
        return $this->options;
    }

    public function count(): int
    {
        return count($this->options);
    }

    public function configure(array $configuration): void
    {
        $keys = static::DEFAULT_OPTION_KEYS;
        if (!empty($configuration)) {
            $keys = GeneralUtility::trimExplode(',', $configuration[0], true);

        }
        foreach ($keys as $key) {
            $label = $this->translate(
                static::PREFIX_OPTION_LABEL_KEY . $key, SI::EXTENSION_KEY
            );
            $option = new Option();
            $option->setValue($key)
                ->setLabel($label);
            $this->options[] = $option;
        }
    }
}
