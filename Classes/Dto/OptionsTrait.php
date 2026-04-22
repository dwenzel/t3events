<?php

namespace DWenzel\T3events\Dto;

use DWenzel\T3events\Domain\Repository\DemandedRepositoryInterface;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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

/**
 * Trait OptionsTrait
 */
trait OptionsTrait
{
    /**
     * @var QueryResultInterface
     */
    protected $options = [];

    public function count(): int
    {
        return count($this->options);
    }

    public function getOptions(): iterable
    {
        return $this->options;
    }

    public function configure(array $configuration): void
    {
        if ($configuration[0] === SI::ALL) {
            $this->options = $this->getOptionRepository()->findAll();

            return;
        }

        $this->options = $this->getOptionRepository()->findMultipleByUid(
            $configuration[0],
            FilterInterface::DEFAULT_SORT_FIELD
        );
    }

    abstract public function getOptionRepository(): DemandedRepositoryInterface;
}
