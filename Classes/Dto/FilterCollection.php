<?php

namespace DWenzel\T3events\Dto;

use Countable;
use Iterator;

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
 * Class FilterCollection
 */
class FilterCollection implements Iterator, Countable
{
    /**
     * An array holding the filters. The key of the array items is the
     * spl_object_hash of the given $filter.
     *
     * [
     *   spl_object_hash => $filter
     * ]
     *
     * @var array
     */
    protected $storage = [];

    /**
     * Returns the current storage entry
     *
     * @return FilterInterface|false
     */
    public function current(): FilterInterface
    {
        return current($this->storage);
    }

    /**
     * Moves to the next storage entry.
     * @todo: Set return type to void in v12 as breaking patch and drop #[\ReturnTypeWillChange]
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        next($this->storage);
    }

    /**
     * Returns the index at which the iterator currently is.
     *
     * The key is an object hash
     *
     * @return int
     * @todo: Set return type to mixed in v12 as breaking patch and drop #[\ReturnTypeWillChange]
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->storage);
    }

    /**
     * Checks if the pointer of the storage points to a valid position
     * @return bool
     * @todo: Set return type to bool in v12 as breaking patch and drop #[\ReturnTypeWillChange]
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return current($this->storage) !== false;
    }

    /**
     * Rewinds to the first storage element
     * @todo: Set return type to void in v12 as breaking patch and drop #[\ReturnTypeWillChange]
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->storage);
    }

    /**
     * Attaches the filter
     *
     * @param FilterInterface $filter
     */
    public function attach(FilterInterface $filter)
    {
        $this->storage[spl_object_hash($filter)] = $filter;
    }

    /**
     * Checks whether a filter is contained in collection
     *
     * @param FilterInterface $filter
     * @return bool
     */
    public function contains(FilterInterface $filter): bool
    {
        return isset($this->storage[spl_object_hash($filter)]);
    }

    /**
     * Removes a filter from the collection
     *
     * @param FilterInterface $filter
     */
    public function detach(FilterInterface $filter)
    {
        unset($this->storage[spl_object_hash($filter)]);
    }

    /**
     * Count elements of collection
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->storage);
    }
}
