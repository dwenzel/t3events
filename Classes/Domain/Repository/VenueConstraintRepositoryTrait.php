<?php
namespace DWenzel\T3events\Domain\Repository;

use DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for Venue constraint repositories
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait VenueConstraintRepositoryTrait
{
    /**
     * Create Venue constraints from demand (time restriction)
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\VenueAwareDemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     */
    public function createVenueConstraints(QueryInterface $query, VenueAwareDemandInterface $demand)
    {
        $venueConstraints = [];
        $venueField = $demand->getVenueField();
        if ($demand->getVenues() !== null) {
            $venues = GeneralUtility::intExplode(',', $demand->getVenues(), true);
            foreach ($venues as $venue) {
                $venueConstraints[] = $query->contains($venueField, $venue);
            }
        }

        return $venueConstraints;
    }
}
