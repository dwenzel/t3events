<?php

namespace DWenzel\T3events\Dto\Factory;

use DWenzel\T3events\Dto\FilterInterface;
use DWenzel\T3events\Dto\FilterResolver;
use DWenzel\T3events\Dto\FilterResolverInterface;
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
 * Class FilterFactory
 * Provides filter for forms
 */
class FilterFactory
{
    use ObjectManagerTrait;

    /**
     * @var FilterResolverInterface
     */
    protected $filterResolver;

    /**
     * @param FilterResolverInterface $filterResolver
     */
    public function injectFilterResolver(FilterResolverInterface $filterResolver)
    {
        $this->filterResolver = $filterResolver;
    }

    /**
     * @param string $key
     * @param array $configuration
     * @return FilterInterface
     */
    public function get(string $key = '', array $configuration = []): FilterInterface
    {
        $filterClass = $this->getFilterResolver()->resolve($key);

        /** @var FilterInterface $filter */
        $filter = $this->objectManager->get($filterClass);
        $filter->configure($configuration);

        return $filter;
    }

    /**
     * @return FilterResolverInterface
     */
    public function getFilterResolver(): FilterResolverInterface
    {
        if (!$this->filterResolver instanceof FilterResolverInterface) {
            $this->filterResolver = new FilterResolver();
        }
        return $this->filterResolver;
    }
}
