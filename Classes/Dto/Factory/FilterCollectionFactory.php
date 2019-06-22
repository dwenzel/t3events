<?php

namespace DWenzel\T3events\Dto\Factory;

use DWenzel\T3events\Dto\FilterCollection;
use DWenzel\T3events\Dto\NullFilter;
use DWenzel\T3events\Object\ObjectManagerTrait;

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
 * Class FilterCollectionFactory
 */
class FilterCollectionFactory
{
    use FilterFactoryTrait,ObjectManagerTrait;

    /**
     * Builds a FilterCollection from configuration
     *
     * @param array $configuration
     * @return FilterCollection
     */
    public function get(array $configuration): FilterCollection
    {
        /** @var FilterCollection $collection */
        $collection = $this->objectManager->get(FilterCollection::class);

        if(empty($configuration)) {
            return $collection;
        }

        foreach ($configuration as $key => $singleConfiguration){
            if (!is_array($singleConfiguration)) {
                $singleConfiguration = [$singleConfiguration];
            }
            $filter = $this->filterFactory->get($key, $singleConfiguration);
            if ($filter instanceof NullFilter)
            {
                continue;
            }
            $collection->attach($filter);
        }

        return $collection;
    }
}
