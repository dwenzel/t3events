<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
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
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
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
