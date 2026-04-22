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
    #[\Override]
    public function current(): FilterInterface
    {
        return current($this->storage);
    }

    /**
     * Moves to the next storage entry.
     */
    #[\Override]
    public function next(): void
    {
        next($this->storage);
    }

    /**
     * Returns the index at which the iterator currently is.
     *
     * The key is an object hash
     */
    #[\Override]
    public function key(): int
    {
        return key($this->storage);
    }

    /**
     * Checks if the pointer of the storage points to a valid position
     */
    #[\Override]
    public function valid(): bool
    {
        return current($this->storage) !== false;
    }

    /**
     * Rewinds to the first storage element
     */
    #[\Override]
    public function rewind(): void
    {
        reset($this->storage);
    }

    /**
     * Attaches the filter
     */
    public function attach(FilterInterface $filter): void
    {
        $this->storage[spl_object_hash($filter)] = $filter;
    }

    /**
     * Checks whether a filter is contained in collection
     */
    public function contains(FilterInterface $filter): bool
    {
        return isset($this->storage[spl_object_hash($filter)]);
    }

    /**
     * Removes a filter from the collection
     */
    public function detach(FilterInterface $filter): void
    {
        unset($this->storage[spl_object_hash($filter)]);
    }

    /**
     * Count elements of collection
     */
    #[\Override]
    public function count(): int
    {
        return count($this->storage);
    }
}
