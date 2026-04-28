<?php

namespace DWenzel\T3events\Domain\Repository;

use DWenzel\T3events\Utility\GeoCoder;
use TYPO3\CMS\Extbase\Annotation\Inject;
use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class LocationConstraintRepositoryTrait
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait LocationConstraintRepositoryTrait
{
    /**
     * @var GeoCoder
     * @Inject
     */
    protected GeoCoder $geoCoder;

    /**
     * Create location constraints from demand
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     */
    public function createLocationConstraints(QueryInterface $query, SearchAwareDemandInterface $demand): array
    {
        $locationConstraints = [];

        if ($demand->getSearch()) {
            $locationConstraints = [];
            $search = $demand->getSearch();

            // search by bounding box
            $bounds = $search->getBounds();
            $location = $search->getLocation();
            $radius = $search->getRadius();

            if (!empty($location)
                && !empty($radius)
                && empty($bounds)
            ) {
                $geoLocation = $this->geoCoder->getLocation($location);
                if ($geoLocation !== false) {
                    $bounds = $this->geoCoder->getBoundsByRadius($geoLocation['lat'], $geoLocation['lng'], $radius / 1000);
                }
            }
            if ($bounds &&
                !empty($bounds['N']) &&
                !empty($bounds['S']) &&
                !empty($bounds['W']) &&
                !empty($bounds['E'])
            ) {
                $locationConstraints[] = $query->greaterThan('latitude', $bounds['S']['lat']);
                $locationConstraints[] = $query->lessThan('latitude', $bounds['N']['lat']);
                $locationConstraints[] = $query->greaterThan('longitude', $bounds['W']['lng']);
                $locationConstraints[] = $query->lessThan('longitude', $bounds['E']['lng']);
            }
        }

        return $locationConstraints;
    }
}
