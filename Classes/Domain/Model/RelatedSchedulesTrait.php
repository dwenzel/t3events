<?php

namespace DWenzel\T3events\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
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

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Trait RelatedSchedulesTrait
 * Provides related schedules
 */
trait RelatedSchedulesTrait
{
    /**
     * related schedules
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $relatedSchedules;

    /**
     * @return ObjectStorage
     */
    public function getRelatedSchedules(): ObjectStorage
    {
        return $this->relatedSchedules;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance> $relatedSchedules
     */
    public function setRelatedSchedules(ObjectStorage $relatedSchedules)
    {
        $this->relatedSchedules = $relatedSchedules;
    }

    /**
     * Add a related schedule
     */
    public function addRelatedSchedule(Performance $schedule)
    {
        $this->relatedSchedules->attach($schedule);
    }

    /**
     * removes a related schedule
     */
    public function removeRelatedSchedule(Performance $schedule)
    {
        $this->relatedSchedules->detach($schedule);
    }
}
