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

use DWenzel\T3events\Utility\SettingsInterface as SI;

/**
 * Class FilterResolver
 */
class FilterResolver implements FilterResolverInterface
{

    /**
     * @var array Map of keys to Filter Classes
     *
     * Override in order to add implementation
     */
    static $map = [
        SI::AUDIENCES => AudienceFilter::class,
        SI::EVENT_TYPES => EventTypeFilter::class,
        SI::GENRES => GenreFilter::class,
        SI::VENUES => VenueFilter::class,
    ];

    /**
     * Resolves a filter class by key
     *
     * If no filter exists for the key of NullFilter::class is returned
     *
     * @param string $key
     * @return string class name
     */
    public function resolve(string $key): string
    {
        if (!array_key_exists($key, static::$map)) {
            return NullFilter::class;
        }

        return static::$map[$key];
    }
}
